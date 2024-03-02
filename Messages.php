<?php

namespace DavesValidator\Validator;


class Messages
{
    // Dictionary for error messages
    public static array $messages = [
        'en' => [
            'required' => [ "Field ", " does not exist"],
            'field' => ["Field "," contains wrong data "],
            'method' => ["Method ", " doesn't exist"]
        ],
        'it' => [
            'required' => ["Campo ", " non esiste"],
            'field' => ["Campo ", " contiene dati errati"],
            'method' => ["Metodo ", " non esiste"]
        ],

        'es' => [
            'required' => ["Campo ", " no existe"],
            'field' => ["Campo ", " contiene datos incorrectos"],
            'method' => ["Método ", " no existe"]
        ],

        'de' => [
            'required' => ["Feld ", " existiert nicht"],
            'field' => ["Feld ", " enthält falsche Daten"],
            'method' => ["Methode ", " existiert nicht"]
        ],

        'fr' => [
            'required' => ["Champ ", " n'existe pas"],
            'field' => ["Champ ", " contient des données incorrectes"],
            'method' => ["Méthode ", " n'existe pas"]
        ],

        'ru' => [
            'required' => ["Поле ", " не существует"],
            'field' => ["Поле ", " содержит неверные данные"],
            'method' => ["Метод ", " не существует"]
        ],

        /// ...your language
    ];

    /**
     * Here we can rename field in error message
     * @var array|array[]
     */
    public static array $filedNames = [
      'en' => [
          'name' => 'NAME',
          'time' => 'TIME'
      ]
    ];

    public static function getArr($lang, $customMessagesMass)
    {
        return $customMessagesMass[$lang];
    }

    public static function getMessages($lang, $customMessagesMass)
    {
        $l = strtolower($lang);
        return array_key_exists($l, $customMessagesMass)
            ? self::getArr($l, $customMessagesMass)
            : self::getArr('en', $customMessagesMass);
    }

    public static function getCustomFieldName($field, $lang, $customFieldName)
    {
        return (isset($customFieldName[$lang][$field]) && is_string($customFieldName[$lang][$field]))
            ? $customFieldName[$lang][$field]
            : $field;
    }

    public static function getMessageField($field, $data, $printField, $printData, $lang, $way, $customMessagesMass, $customFieldName ): string
    {
        $massLang = self::getMessages($lang, $customMessagesMass);
        $mass = $massLang[$way];
        $field = self::getCustomFieldName($field, $lang, $customFieldName);
        if ($data === null)
        {
            $printData = false;
        }
        $err = $printData
            ? ($printField
                ? $mass[0]."'".$field."'".rtrim($mass[1]).": ".$data
                : $mass[0].rtrim($mass[1]).": ".$data)
            : ($printField
                ? $mass[0]."'".$field."'".$mass[1]
                : $mass[0].$mass[1]);
        $res = preg_replace("/\s+/u", " ", $err);
        return $res;
    }
}