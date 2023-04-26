<?php

class LocaleEn implements LocaleInterface {
    use FrameworkLocaleEn;

    public function inp_password()
    {
        return "Password";
    }

    public function username_regex_failed()
    {
        return "Invalid username.";
    }

    public function button_signup()
    {
        return "Register Account";
    }

    public function signup_success($email)
    {
        return "Account creation successful. Follow the link sent to ".
            "<b>$email</b> to activate your account.";
    }

}
