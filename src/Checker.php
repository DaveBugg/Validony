<?php
namespace DavesValidator;

class Checker
{
    // CONST examples
    public const required = 'required';
    public const time = 'time';

    public const date_time = 'date_time';
    public const first_name = 'first_name';
    public const last_name = 'last_name';
    public const email = 'email';
    public const phone = 'phone';
    public const country2 = 'country2';

    public const number = 'number';
    public const is_bool = 'is_bool';

    public const login = 'login';
    public const password = 'password';

    // Validate methods examples
    /**
     * The function always receives a value and contains a test condition
     * @param $val
     * @return bool
     */
    public static function time($val): bool
    {
        return (preg_match('/^[\d]{2}:[\d]{2}$/u', $val) || preg_match('/^[\d]{2}:[\d]{2}:[\d]{2}$/u', $val));
    }
    public static function date_time($val): bool|int
    {
        return (preg_match('/^\d\d\d\d-\d\d-\d\d(T|\s)\d\d:\d\d:\d\d$/ui', $val));
    }
    //
    public static function first_name($val): bool|int
    {
        return (preg_match('/^[a-яa-záéíñóúüàèìîòùäößẞæøåØąćęłńśźżăâșțôëêïçûÿœ \'’-]{2,35}$/ui', $val));
    }
    public static function last_name($val): bool|int
    {
        return (preg_match('/^[a-яa-záéíñóúüàèìîòùäößẞæøåØąćęłńśźżăâșțôëêïçûÿœ \'’-]{2,35}$/ui', $val));
    }
    public static function email($val): bool|int
    {
        return (preg_match('/^[0-9a-z._\-]{2,30}@[0-9a-z._\-]{2,25}\.[a-z]{2,15}$/ui', $val));
    }
    public static function phone($val): bool|int
    {
        return (preg_match('/^\+?\d{7,18}$/', $val));
    }
    public static function country2($val): bool|int
    {
        return (preg_match('/^[a-zA-Z]{2}$/', $val));
    }
    //
    public static function number($val): bool|int
    {
        return (preg_match('/^-?[\d]{1,50}$/', $val));
    }
    public static function is_bool($val): bool
    {
        return (is_bool($val) || $val === 'true' || $val === 'false');
    }

    public static function login($val): bool|int
    {
        return(preg_match('/^[a-z0-9_@.]{3,40}$/ui', $val));
    }
    public static function password($val): bool|int
    {
        return (preg_match('/^[0-9a-z_\-.,:@!#$%^&*]{8,40}/ui', $val));
    }


    public static function Get(&$v)
    {
        return (isset($v))?$v:false;
    }
    public static function crypto_w($val): bool|int
    {
        return (preg_match('/^[\w]{24,88}$/u', $val));
    }

}