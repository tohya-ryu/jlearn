<?php

interface FrameworkStore {

    public function __construct($config);

    public function connection($key = false);

    public function migrate($dir);

    public function begin_transaction();
    public function commit_transaction();
    public function query($sql, $types = null, $params = null);
    public function insert($sql, $types = null, $params = null);
    public function update($sql, $types = null, $params = null);
    public function delete($sql, $types = null, $params = null);

}
