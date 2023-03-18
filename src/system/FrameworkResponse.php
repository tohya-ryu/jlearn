<?php

class FrameworkResponse {
    use FrameworkSingleton;
    use FrameworkMagicGet;
    private static $magic_get_attr = array(
        'type'
    );

    const HTML = 1;
    const JSON = 2;

    private $type;

    public function set_type($type)
    {
        $this->type = $type;
    }

}
