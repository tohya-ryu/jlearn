<?php

class FrameworkMariadb implements FrameworkStore {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'type', 'name', 'charset', 'engine', 'collate'
    );

    private $name;
    private $key;
    private $type;
    private $engine;
    private $charset;
    private $collate;

    private $transaction_f;

    private $connections;
    private $default_conn_key;

    private $statement;
    private $result;

    public function __construct($config)
    {
        $this->name = $config['name'];
        $this->key = $config['key'];
        $this->type = $config['type'];
        $this->engine = $config['engine'];
        $this->charset = $config['charset'];
        $this->collate = $config['collate'];
        $this->transaction_f = false;
        $this->statement = null;
        $this->result =  null;

        $this->connections = array();

        $this->init();

        foreach ($config['connections'] as $conn_conf) {
            $this->connections[$conn_conf['key']] = new FrameworkConnection(
                $this, $conn_conf);
            if ($conn_conf['default'])
                $this->default_conn_key = $conn_conf['key'];
        }
    }

    public function connection($key = false)
    {
        if ($key) {
            return $this->connections[$key];
        } else {
            return $this->connections[$this->default_conn_key];
        }
    }

    private function init()
    {
        switch ($this->type) {
        case 'mysql/mariadb':
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            break;
        }
    }

    public function migrate($dir)
    {
        foreach ($this->connections as $conn) {
            $conn->migrate($dir);
        }
    }

    public function begin_transaction()
    {
        $this->transaction_f = true;
        // Whenever a query during a transaction fails
        // ROLLBACK needs to be executed
    }

    public function commit_transaction()
    {
        $this->transaction_f = false;
        // execute commit
    }

    public function rollback()
    {
    }

    public function pquery($sql, $types, ...$params)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $this->result = $stmt->get_result();
            $stmt->close();
            return $this->result;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }

    }

    public function prepare($sql)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->statement = $conn->prepare($sql);
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function bind($types, &...$params)
    {
        try {
            $this->statement->bind_param($types, ...$params);
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function exec()
    {
        try {
            $this->statement->execute();
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function get_result()
    {
        try {
            return $this->statement->get_result();
        } catch (Exception $e) {
            $this->handle_exception($e);
        }
    }

    public function close_statement()
    {
        try {
            $this->statement->close();
        } catch (Exception $e) {
            $this->handle_exception($e);
        }
    }

    public function query($sql, $types = null, $params = null)
    {
    }

    public function insert($sql, $types = null, $params = null)
    {
    }

    public function update($sql, $types = null, $params = null)
    {
    }

    public function delete($sql, $types = null, $params = null)
    {
    }

    public function handle_exception($e)
    {
        echo "DB error :(";
        exit();
    }


}
