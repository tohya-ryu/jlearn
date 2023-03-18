<?php

class MainController extends FrameworkControllerBase {


    public function index()
    {
        echo "landing page";
        $storemanager = FrameworkStoreManager::get();
        $conn = $storemanager->store()->connection()->get();
        var_dump($conn);
    }

}
