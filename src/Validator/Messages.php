<?php

namespace DavesValidator\Validator;


class Messages
{
    // Dictionary for error messages
    public static array $messages = [
        'en' => [
            'required' => "Field :field does not exist",
            'field' => "Field :field contains wrong data",
            'method' => "Method :field doesn't exist"
        ],
        'it' => [
            'required' => "Campo :field non esiste",
            'field' => "Campo :field contiene dati errati",
            'method' => "Metodo :field non esiste"
        ],

        'es' => [
            'required' => "Campo :field no existe",
            'field' => "Campo :field contiene datos incorrectos",
            'method' => "Método :field no existe"
        ],

        'de' => [
            'required' => "Feld :field existiert nicht",
            'field' => "Feld :field enthält falsche Daten",
            'method' => "Methode :field existiert nicht"
        ],

        'fr' => [
            'required' => "Champ :field n'existe pas",
            'field' => "Champ :field contient des données incorrectes",
            'method' => "Méthode :field n'existe pas"
        ],

        'ru' => [
            'required' => "Поле :field не существует",
            'field' => "Поле :field содержит неверные данные",
            'method' => "Метод :field не существует"
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
        $messageTemplate = $massLang[$way];
        $field = self::getCustomFieldName($field, $lang, $customFieldName);
        
        if ($data === null) {
            $printData = false;
        }
        
        $fieldReplacement = $printField ? "'{$field}'" : $field;
        $err = str_replace(':field', $fieldReplacement, $messageTemplate);
        
        if ($printData) {
            $err .= ": " . mb_convert_encoding($data, "UTF-8");
        }
        
        $res = preg_replace("/\s+/u", " ", $err);
        return $res;
    }
}