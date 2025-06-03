<?php

namespace DavesValidator\Validator;

class Validon
{

    /**
     * Validate an array of fields based on specified rules.
     *
     * @param array $post - $_POST array
     * @param array $fields
     *      Example: $rules = [
     *          'bank' => [Checker::type], // Checker is the $checkerClass
     *          'name' => [Checker::name], // or ['name'] (use string or variable - method's name)
     *      ];
     * @param array|bool $customMessagesMass - Public static array, for example \Messages::$messages
     * @param array|bool $customFieldName - Public static array, for example \Messages::$fieldNames
     * @param mixed $checkerClass - Checker class with variables and methods to validate, default - Checker::class
     * @param array $callbackMethod - Callback class and static method to send validation error on $doCallback, initialized as ['Validator\\Validony', 'AnswerErrorCallback']
     * @param bool $doCallback - Call function ($callbackMethod) to return an error message if no valid field is found - true/false
     * @param string $errLanguage - Language key to find messages array in $messages and $fieldNames (default: en)
     * @param bool $printField - Add field name to the error message
     * @param bool $printData - Add field's value to the error message
     * @param bool $getAllErrors - Return all founded errors after validating all fields
     * @param bool $getFields - If true, add error fields names to the $errors array (getErrors method)
     *
     * @return array
     */

    public static function CheckData(array $post, array $fields, array|bool $customMessagesMass = false, array|bool $customFieldName = false,
                                     mixed $checkerClass = false, array $callbackMethod = [], bool $doCallback = false, string $errLanguage = 'en',
                                     bool $printField = true, bool $printData = false, bool $getAllErrors = false, bool $getFields = false): array
    {
        $validator = new Validony($post, $customMessagesMass, $customFieldName, $checkerClass, $callbackMethod, $errLanguage, $printField, $printData, $getAllErrors, $doCallback);
        $validator->CheckData($fields);
        $valid = $validator->isValid();
        $errors = $validator->getErrors($getFields);
        return [$valid, $errors];
    }


    /**
     * Validate an array of fields using a static method specified by name.
     *
     * @param array $post - $_POST array
     * @param string $methodName - Static method for validation (returns an array with rules). ValidateList attempts to find the method you specify in all classes in the '/Lists/' folder located in the main path of the lib.
     *                           Example: 'TimeValidator'
     * @param array|bool $customMessagesMass - Choose your own path to lists, for example, MAIN_PATH.'/Lists/'
     * @param array|bool $customFieldName - Path for your classes for Lists, initialized as false (use Lists in the lib path)
     * @param mixed|false $checkerClass - Checker class with variables and methods to validate, default - Checker::class
     * @param array $callbackMethod - Callback class and static method to send validation error on $doCallback, initialized as ['Validator\\Validony', 'AnswerErrorCallback']
     * @param bool $doCallback - Call function ($callbackMethod) to return an error message if no valid field is found - true/false
     * @param string $errLanguage - Language to find the messages array in $messages and $fieldNames (key for messages array, default: en)
     * @param bool|string $pathOfLists - Path for your classes for Lists, initialized as false (use Lists in the lib path)
     * @param bool|string $namespaceOfListsClasses - Namespace of your classes located in the Lists folder, initialized as false (use __NAMESPACE__.'\\Lists\\')
     * @param bool $printField - Add field name to the error message
     * @param bool $printData - Add field's value to the error message
     * @param bool $getAllErrors - Return all found errors after validating all fields
     * @param bool $getFields - If true, add error fields names to the $errors array (getErrors method)
     *
     * @return array
     */

    public static function ValidateList(array       $post, string $methodName, array|bool $customMessagesMass = false, array|bool $customFieldName = false,
                                        mixed       $checkerClass = false, array $callbackMethod = [], bool $doCallback = false, string $errLanguage = 'en',
                                        bool|string $pathOfLists = false, bool|string $namespaceOfListsClasses = false,
                                        bool        $printField = true, bool $printData = false, bool $getAllErrors = false, bool $getFields = false): array
    {
        $validator = new Validony($post, $customMessagesMass, $customFieldName, $checkerClass, $callbackMethod, $errLanguage, $printField, $printData, $getAllErrors, $doCallback);
        $validator->ValidateList($methodName, $pathOfLists, $namespaceOfListsClasses);
        $valid = $validator->isValid();
        $errors = $validator->getErrors($getFields);
        return [$valid, $errors];
    }

    /**
     * Validate an array of fields based on specified rules.
     *
     * @param array $post - $_POST array
     * @param array $fields
     *   Example: $fields = [
     *       'bank' => [Checker::type]  // The method will check 'bank1', 'bank_new', 'bank2', and other fields in POST starting with 'bank'
     *   ];
     * @param array|bool $customMessagesMass - Choose your own path to lists, for example, MAIN_PATH.'/Lists/'
     * @param array|bool $customFieldName - Path for your classes for Lists, initialized as false (use Lists in the lib path)
     * @param mixed|false $checkerClass - Checker class with variables and methods to validate, default - Checker::class
     * @param array $callbackMethod - Callback class and static method to send validation error on $doCallback, initialized as ['Validator\\Validony', 'AnswerErrorCallback']
     * @param bool $doCallback - Call function ($callbackMethod) to return an error message if no valid field is found - true/false
     * @param string $errLanguage - Language to find messages array in $messages and $fieldNames (key for messages array, default: en)
     * @param bool $printField - Add field name to the error message
     * @param bool $printData - Add field's value to the error message
     * @param bool $getAllErrors - Return all found errors after validating all fields
     * @param bool $getFields - If true, add error field names to the $errors array (getErrors method)
     *
     * @return array
     */
    public static function CheckLikeFieldsData(array $post, array $fields, array|bool $customMessagesMass = false, array|bool $customFieldName = false,
                                               mixed $checkerClass = false, array $callbackMethod = [], bool $doCallback = false, string $errLanguage = 'en',
                                               bool  $printField = true, bool $printData = false, bool $getAllErrors = false, bool $getFields = false): array
    {
        $validator = new Validony($post, $customMessagesMass, $customFieldName, $checkerClass, $callbackMethod, $errLanguage, $printField, $printData, $getAllErrors, $doCallback);
        $validator->CheckLikeFieldsData($fields);
        $valid = $validator->isValid();
        $errors = $validator->getErrors($getFields);
        return [$valid, $errors];
    }
}