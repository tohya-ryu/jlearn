<?php

class FrameworkControllerBase {

    protected $request;
    protected $lang;
    protected $response;

    public function set_request()
    {
        $this->request = FrameworkRequest::get();
    }

    public function init_response()
    {
        $this->response = FrameworkResponse::get();
    }

    protected function init_language()
    {
        $request = FrameworkRequest::get();
        $request->init_accepted_languages();
        $this->lang = FrameworkLanguage::get();
        $this->lang->from_default(); 
        $this->lang->from_browser(); 
        $this->lang->from_cookie(); 
    }

}
