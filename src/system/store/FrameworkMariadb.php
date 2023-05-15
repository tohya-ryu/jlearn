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

    # query builder
    private $query_type;
    private $table;
    private $values;
    private $typeident;
    private $columns;

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

        $this->columns = array();
        $this->values = array();
        $this->typeident = "";

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

    public function query($sql)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->result = $conn->query($sql);
            return $this->result;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function update($sql, $types = null, $params = null)
    {
    }

    public function delete($sql, $types = null, $params = null)
    {
    }

    public function handle_exception($e)
    {
        echo "DB error: $e";
        exit();
    }

    public function escape($str)
    {
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            return $conn->real_escape_string($str);
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }

    public function set($column, $typeident, $value)
    {
        array_push($this->columns, $this->escape($column));
        array_push($this->values, $value);
        $this->typeident .= $typeident;
    }

    public function insert_into($table)
    {
        $this->query_type = 'insert';
        $this->table = $this->escape($table);
    }

    public function update($table)
    {
        $this->query_type = 'update';
        $this->table = $this->escape($table);
    }

    public function run()
    {
        $ret = null;
        switch ($this->query_type) {
        case 'insert':
            $ret = $this->insert();
            break;
        case 'update':
            $ret = $this->update_table()
            break;
        }
        $this->values = array();
        $this->columns = array();
        $this->typeident = "";
        return $ret;
    }

    private function insert()
    {
        $columns = "";
        $values = "";
        $last_key = ArrayUtil::last_key($this->columns);
        foreach ($this->columns as $k => $v) {
            $columns .= "`$v`";
            $values .= "?";
            if (!($k === $last_key)) {
                $columns .= ",";
                $values  .= ",";
            }
        }
        $sql = "INSERT INTO `{$this->table}` ($columns) VALUES ($values);";
        try {
            $conn = $this->connections[$this->default_conn_key]->get();
            $this->pquery($sql, $this->typeident, ...($this->values));
            return $conn->insert_id;
        } catch (Exception $e) {
            if ($this->transaction_f) {
                $this->rollback();
            }
            $this->handle_exception($e);
        }
    }


}
