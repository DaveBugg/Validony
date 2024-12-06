<?php


namespace DavesValidator\Lists;

use DavesValidator\Checker;

class MainList
{
    /**
     * Key - names of fields
     * Values - rules for validate data (names checker class functions):
     * - constants and strings allowed,
     * - multiple check functions allowed for one field
     * @return array
     */
    public static function LeadValidator(): array
    {
        return [
            'first_name' =>          [Checker::required, 'first_name'],
            'last_name' =>           [Checker::required, Checker::last_name],
            'phone' =>               ['phone'],
            'country2' =>            ['country2'],
            'email' =>               [Checker::required, 'email']
        ];
    }
    public static function TimeValidator(): array
    {
        return [
            'time' =>       [Checker::required, Checker::time]
        ];
    }
    public static function DateValidator(): array
    {
        return [
            'date' =>       [Checker::required, Checker::date_time]
        ];
    }
    public static function LoginValidator(): array
    {
        return [
            'login'          =>  [Checker::required, Checker::login],
            'password'       =>  [Checker::required, Checker::password]
        ];
    }

}