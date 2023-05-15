<?php

interface FrameworkStore {

    public function __construct($config);

    public function connection($key = false);

    public function migrate($dir);

    public function begin_transaction();
    public function commit_transaction();
    public function rollback();
    public function handle_exception($e);
    public function query($sql);
    public function pquery($sql, $types, ...$params);
    public function update($sql, $types = null, $params = null);
    public function delete($sql, $types = null, $params = null);

    public function insert_into($table);
    public function update($table);
    public function set($column, $typeident, $value);
    public function run();

    public function prepare($sql);
    public function bind($types, &...$params);
    public function exec();
    public function close_statement();

}
