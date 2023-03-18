<?php

trait FrameworkLocaleEn {

    public function validation_error_required()
    {
        return "This field is required.";
    }

    public function validation_error_minlen($n)
    {
        return "Input requires at least $n symbols.";
    }

    public function validation_error_maxlen($n)
    {
        return "Input must not exceed $n symbols.";
    }

    public function validation_error_email()
    {
        return "E-Mail address appears to be invalid.";
    }

    public function validation_error_match_input($str)
    {
        return "Does not to match $str";
    }

    public function validation_csrf_check_failed()
    {
        return "Internal error. Please try again.";
    }

    public function validation_errors_notice()
    {
        return "Form validation failed. Please follow the respective ".
            "input hints below.";
    }


}
