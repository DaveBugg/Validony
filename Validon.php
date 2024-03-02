<?php

namespace DavesValidator\Validator;

class Validon
{

    /**
     * @param array $post  - $_POST array
     * @param array $fields
     *      example $rules = [
     *                    'bank' => [Checker::type], (Checker - $checkerClass;)
     *                    'name' => [Checker::name], // or ['name'] (use string or variable - method's name)
     *                  ];
     * @param array|bool $customMessagesMass    - public static array, for examole \Messages::$messages
     * @param array|bool $customFieldName       - public static array, for examole    \Messages::$filedNames
     * @param mixed $checkerClass               - checker class with vars and methods to validate, default - Checker::class
     * @param array $callbackMethod             - callback class and static method to send validate error on $doCallback, init - ['Validator\\Validony', 'AnswerErrorCallback']
     * @param bool $doCallback                  - call function ($callbackMethod) for return error message if no valid filed found - true/false
     * @param string $errLanguage               - language to find messages array in $messages and $fieldNames. ( key for messages array, default en)
     * @param bool $printField                  - for add filed name to error message
     * @param bool $printData                   - for add field's value to error message
     * @param bool $getAllErrors                - returns all founded errors after validate all field
     * @param bool $returnString                - if true - return errors string                                                                   ( getErrors method )
     * @param bool $getFields                   - if true add error fields names to $errors array, works if $returnString - false                  ( getErrors method )
     * @return array
     */
    public static function CheckData(array $post, array $fields, array|bool $customMessagesMass = false, array|bool $customFieldName = false,
                                     mixed $checkerClass = false, array $callbackMethod = [], bool $doCallback = false, string $errLanguage = 'en',
                                     bool $printField = true, bool $printData = false, bool $getAllErrors = false,
                                     bool $returnString = false, bool $getFields = false): array
    {
        $validator = new Validony($post, $customMessagesMass, $customFieldName , $checkerClass, $callbackMethod, $errLanguage);
        $validator->CheckData($fields, $doCallback, $printField, $printData, $getAllErrors);
        $valid = $validator->isValid();
        $errors = $validator->getErrors($returnString, $getFields);
        return [$valid, $errors];
    }


    /**
     * @param array $post - $_POST array
     * @param string $methodName - Static method for validation (return array with rules), ValidateList tries to find the method you type in all classes in folder /Lists/ which locates in lib main path.
     *                              example: 'TimeValidator'
     * @param array|bool $customMessagesMass - choose your own path to lists like MAIN_PATH.'/Lists/'
     * @param array|bool $customFieldName    - path for your classes for Lists, init - false (use Lists in lib path)
     * @param mixed|false $checkerClass     - checker class with vars and methods to validate, default - Checker::class
     * @param array $callbackMethod - callback class and static method to send validate error on $doCallback, init - ['Validator\\Validony', 'AnswerErrorCallback']
     * @param bool $doCallback  - call function ($callbackMethod) for return error message if no valid filed found - true/false
     * @param string $errLanguage - language to find messages array in $messages and $fieldNames. ( key for messages array, default en)
     * @param bool|string $pathOfLists - path for your classes for Lists, init - false (use Lists in lib path)
     * @param bool|string $namespaceOfListsClasses - namespace of your classes locates in Lists folder, init - false (use __NAMESPACE__.'\\Lists\\' )
     * @param bool $printField - for add filed name to error message
     * @param bool $printData - for add field's value to error message
     * @param bool $getAllErrors - returns all founded errors after validate all field
     * @param bool $returnString  - if true - return errors string                                                               ( getErrors method )
     * @param bool $getFields - if true add error fields names to $errors array, works if $returnString - false                  ( getErrors method )
     * @return array
     */
    public static function ValidateList(array       $post, string $methodName, array|bool $customMessagesMass = false, array|bool $customFieldName = false,
                                        mixed       $checkerClass = false, array $callbackMethod = [], bool $doCallback = false, string $errLanguage = 'en',
                                        bool|string $pathOfLists = false, bool|string $namespaceOfListsClasses = false,
                                        bool        $printField = true, bool $printData = false, bool $getAllErrors = false,
                                        bool        $returnString = false, bool $getFields = false): array
    {
        $validator = new Validony($post, $customMessagesMass, $customFieldName , $checkerClass, $callbackMethod, $errLanguage);
        $validator->ValidateList($methodName, $pathOfLists, $namespaceOfListsClasses, $doCallback, $printField, $printData, $getAllErrors);
        $valid = $validator->isValid();
        $errors = $validator->getErrors($returnString, $getFields);
        return [$valid, $errors];
    }

    /**
     * @param array $post - $_POST array
     * @param array $fields
     * example:   $fields = [
     *                  'bank' => [Checker::type]  //method will check 'bank1', 'bank_new', 'bank2' and other exist in POST fields starts with 'bank'
     *              ];
     * @param array|bool $customMessagesMass - choose your own path to lists like MAIN_PATH.'/Lists/'
     * @param array|bool $customFieldName    - path for your classes for Lists, init - false (use Lists in lib path)
     * @param mixed|false $checkerClass     - checker class with vars and methods to validate, default - Checker::class
     * @param array $callbackMethod - callback class and static method to send validate error on $doCallback, init - ['Validator\\Validony', 'AnswerErrorCallback']
     * @param bool $doCallback  - call function ($callbackMethod) for return error message if no valid filed found - true/false
     * @param string $errLanguage - language to find messages array in $messages and $fieldNames. ( key for messages array, default en)
     * @param bool $printField - for add filed name to error message
     * @param bool $printData - for add field's value to error message
     * @param bool $getAllErrors - returns all founded errors after validate all field
     * @param bool $returnString  - if true - return errors string                                                               ( getErrors method )
     * @param bool $getFields - if true add error fields names to $errors array, works if $returnString - false                  ( getErrors method )
     * @return array
     */
    public static function CheckLikeFieldsData(array $post, array $fields, array|bool $customMessagesMass = false, array|bool $customFieldName = false,
                                               mixed $checkerClass = false, array $callbackMethod = [], bool $doCallback = false, string $errLanguage = 'en',
                                               bool  $printField = true, bool $printData = false, bool $getAllErrors = false,
                                               bool  $returnString = false, bool $getFields = false): array
    {
        $validator = new Validony($post, $customMessagesMass, $customFieldName , $checkerClass, $callbackMethod, $errLanguage);
        $validator->CheckLikeFieldsData($fields, $doCallback, $printField, $printData, $getAllErrors);
        $valid = $validator->isValid();
        $errors = $validator->getErrors($returnString, $getFields);
        return [$valid, $errors];
    }
}