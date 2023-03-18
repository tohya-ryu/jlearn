<?php

interface LocaleInterface {

    public function validation_error_required();
    public function validation_error_minlen($n);
    public function validation_error_maxlen($n);
    public function validation_error_email();
    public function validation_error_match_input($str);
    public function validation_csrf_check_failed();
    public function validation_errors_notice();

    public function inp_password();

    public function username_regex_failed();

    public function signup_success($email);

}
