<?php

class MainController extends FrameworkControllerBase {


    public function index()
    {
        echo "landing page";
        $storemanager = FrameworkStoreManager::get();
        $conn = $storemanager->store()->connection()->get();
        var_dump($conn);
    }

    public function test()
    {
        $db = FrameworkStoreManager::get()->store();
        #$sql = "INSERT INTO `user` (`name`, `email`, `password`, `activated`)".
        #    " VALUES (?,?,?,1)";
        #$db->pquery($sql, "sss", 'tim', 'tim@tom.com', 'abcdefg');
        
        #$db->prepare($sql);
        #$db->bind("sss", $name, $email, $pswd);

        #$name = 'User 1';
        #$email = 'User1@users.com';
        #$pswd = 'pw_user1';
        #$db->exec();

        #$name = 'User 2';
        #$email = 'User2@users.com';
        #$pswd = 'pw_user2';
        #$db->exec();

        $sql = "SELECT COUNT(`id`) FROM `user` WHERE `email` = ?";
        $email = 'tim@tom.com';
        $res = $db->pquery($sql, "s", $email);
        var_dump($res->fetch_row());

        $sql = "SELECT COUNT(`id`) FROM `user` WHERE `email` = ?";
        $email = 'tam@tom.com';
        $res = $db->pquery($sql, "s", $email);
        var_dump($res->fetch_row());

    }

}
