<?php

# Expected arguments
#--------------------
# 1. Key of database to perform migrations on
# 2. Username
# 3. User email
# 4. User password

require 'system/autoload.inc.php';

Framework::init();

$store_manager = FrameworkStoreManager::get();

if ($argc != 5) {
    echo "invalid number of arguments: 4 required \n";
    exit();
}

$auth = new AuthService(null);

$dbkey = $argv[1];
$user = $argv[2];
$email = $argv[3];
$passw = $auth->hashpw($argv[4]);

$db = $store_manager->store($dbkey);

if (!$db) {
    echo "invalid database key $dbkey \n";
    exit();
}

$db->assoc('name',      's', $user);
$db->assoc('email',     's', $email);
$db->assoc('password',  's', $passw);
$db->assoc('activated', 'i', 1);

try {
    $id = $db->insert('user');
    echo "Successfully created user with id $id.\n";
} catch (Exception $e) {
    echo "Failed to create user: $e\n";
}
