<?php

class FrameworkTemplate {
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'view'
    );

    private $view;
    private $data;
    private $path;

    public function __construct($view, $path, $data)
    {
        $this->view = $view;
        $this->path = $path;
        foreach ($data as $key => $value) {
            if ($key == 'view' || $key == 'path') {
                debug_print_backtrace(0,1);
                trigger_error("Invalid Data Key <b>$key</b>.", E_USER_ERROR);
            }
            $this->$key = $value;
        }
    }

    public function render()
    {
        require $this->path;
    }

    private function base_uri($str)
    {
        if (!empty(AppConf::get('root_dir'))) {
            echo "/".AppConf::get('root_dir')."/$str";
        } else {
            echo "/$str";
        }
    }
}
