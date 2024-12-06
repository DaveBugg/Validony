<?php


namespace DavesValidator;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Validony
{
    private array $data;
    private array $error_fields;
    private array $errors;
    private bool $is_valid;
    private mixed $classCallback;
    private string $methodCallback;
    private bool $customErrorSendMethod = false;
    private string $err_language;
    private mixed $customFieldName;
    private mixed $customMessagesMass;
    private mixed $checkerClass;
    public static array $skip_fields_regex = ['required'];


    /**
     * @param array $post
     * @param array|bool $customMessagesMass
     * @param array|bool $customFieldName
     * @param mixed $checkerClass
     * @param array $callback
     * @param string $errLanguage
     *
     */
    public function __construct(array $post, array|bool $customMessagesMass = false, array|bool $customFieldName = false, mixed $checkerClass = false, array $callback = [], string $errLanguage = 'en' )
    {
        $this->data = $post;
        $this->is_valid = false;
        $this->errors = [];
        $this->error_fields = [];
        $this->err_language = $errLanguage;
        $this->customMessagesMass = $customMessagesMass === false ? Messages::$messages : $customMessagesMass;
        $this->customFieldName = $customFieldName;
        if ($callback !== [] && count($callback) === 2 && method_exists($callback[0], $callback[1]))
        {
            $this->classCallback = $callback[0];
            $this->methodCallback = $callback[1];
            $this->customErrorSendMethod = true;
        }
        else
        {
            $this->classCallback = Validony::class;
            $this->methodCallback = 'AnswerErrorCallback';
        }
        $this->checkerClass = $checkerClass === false ? Checker::class : $checkerClass;

    }

    /**
     * @param $MSG
     * @param int $case
     * Method for return error message
     */
    public static function AnswerErrorCallback($MSG, int $case = 1): void
    {
        switch ($case)
        {
            case 1:
                if (is_array($MSG) && count($MSG) === 2)
                {
                    echo json_encode(['message' => $MSG[0], 'fields' => $MSG[1], 'status' => 'error']);
                }
                else
                {
                    $msg = is_array($MSG) ? implode(', ', $MSG) : $MSG;
                    echo json_encode(['message' => $msg, 'status' => 'error']);
                }
                break;
            case 0:
            default:
                $msg = is_array($MSG) ? implode(', ', $MSG) : $MSG;
                echo json_encode(['message' => $msg, 'status' => 'error']);
                break;
        }
        exit(0);
    }


    /**
     * @return bool
     * check result of validation
     */
    public function isValid(): bool
    {
        return $this->is_valid;
    }

    /**
     * @param false $string - returns string or array
     * @param false $getFields - if $string === false and $getFields === true
     *                         - returns mass with fields and errors
     * @return array|string
     *
     */
    public function getErrors(bool $string = false, bool $getFields = false): array|string
    {
        return $string ? ['errors' => implode(', ', $this->errors)] : ($getFields ? ['errors' => $this->errors, 'fields' => $this->error_fields] : ['errors' => $this->errors] );
    }

    public function sendError($msg, $type = 1): void
    {
        $class = $this->classCallback;
        $method = $this->methodCallback;
        $this->customErrorSendMethod
            ? $class::$method($msg)
            : $class::$method($msg, $type);
    }

    /**
     * Validator class for handling field validation using rules and error messages.
     *
     * @param string $method Static method for validation (returns an array with rules).
     * @param bool|string $pathOfLists Path for your classes for Lists, initialized as false (use Lists in lib path).
     * @param bool|string $namespaceOfListsClasses Namespace of your classes located in the Lists folder, initialized as false (use __NAMESPACE__.'\\Lists\\').
     * @param bool $callback Call function for returning an error message if no valid field is found - true/false.
     * @param bool $printField Add field name to the error message.
     * @param bool $printData Add field's value to the error message.
     * @param bool $getAllErrors Return all found errors after validating all fields.
     *
     * Example:
     * Create class:
     * Params:
     *  1 - post array with data to validate.
     *  2 - error messages array, initialized as \Validator\Messages::$messages.
     *  3 - array for renaming fields in the answer, initialized as Messages::$filedNames.
     *  4 - checker class with variables and methods to validate, default - Checker::class.
     *  5 - callback class and static method to send validation error -> callback, initialized as ['Validator\\Validony', 'AnswerErrorCallback'].
     *  6 - language to find messages array in $messages and $fieldNames.
     *
     * $validator = (new Validony($_POST, \Validator\Messages::$messages, \Validator\Messages::$filedNames , \Validator\Checker::class, ['Validator\\Validony', 'AnswerErrorCallback'], 'en'));
     *
     * Call Method:
     * Params:
     *  1 - Static method for validation (returns an array with rules),
     *      ValidateList tries to find the method you type in all classes in the folder /Lists/ located in the lib main path.
     *      You can choose your path in param 2.
     *  2 - path for your classes for Lists, initialized as false (use Lists in lib path).
     *  3 - namespace of your classes located in the Lists folder, initialized as false (use __NAMESPACE__.'\\Lists\\' ).
     *  4 - $callback - call function for returning an error message if no valid field is found - true/false.
     *  5 - $printField - add field name to the error message.
     *  6 - $printData - add field's value to the error message.
     *  7 - $getAllErrors - return all founded errors after validating all fields.
     *
     * $validator->ValidateList('TimeValidator', false, false, true, true, true);
     *
     * Check IsValid: $valid = $validator->isValid();
     *
     * Get Errors:
     * Params:
     *  1 - $string - use to return errors as a string or array.
     *  2 - $getFields - use to get fields which called error on the validation process.
     *      if 1 - false, 2 -true: returns an associative array with errors and fields names.
     *
     * $errors = $validator->getErrors(false, true);
     */
    public function ValidateList(string $method, bool|string $pathOfLists = false, bool|string $namespaceOfListsClasses = false, bool $callback = false, bool $printField = true, bool $printData = false, bool $getAllErrors = true): void
    {
        try {
            $found_class = false;
            $classes = $this->getClassAndMethodForList($pathOfLists, $namespaceOfListsClasses);
            foreach ($classes as $class)
            {
                $m = $class['ns'].$class['file'];
                if (class_exists($m) && method_exists($m, $method))
                {
                    $found_class = $m;
                    break;
                }
            }
            if ($found_class)
            {
                $valid = $this->CheckData($found_class::$method(), $callback, $printField, $printData, $getAllErrors);
                $this->is_valid = $valid;
            }
            else
            {
                $this->is_valid = false;
                $this->errors[] = "Undefined method $method";
                $this->error_fields[] = 'Method';
                if ($callback)
                {
                    $this->sendError("Undefined method $method");
                }
            }
        }catch (\Exception $e)
        {
            echo $e->getMessage();
            die();
        }
    }


    /**
     * Validate an array of fields based on specified rules.
     *
     * @param array $fields Array of rules to validate.
     * @param mixed $CallBack Callback function or class/method for handling validation errors.
     * @param mixed $printField Option to include field names in the error message (true/false).
     * @param mixed $printData Option to include field values in the error message (true/false).
     * @param bool $getAllErrors Option to return all found errors after validating all fields (true/false).
     *
     * Example:
     * Create rules array:
     * $fields = [
     *      'time' => [Checker::required, Checker::time],
     *      'test' => [Checker::checkBoxOnOff], // String variable with the name of a static method in your Checker class
     *      'name' => ['required', 'name'], // Static method with the same name should exist in your Checker class
     * ];
     *
     * Create class:
     * Params:
     *  1 - post array with data to validate.
     *  2 - error messages array, initialized as \Validator\Messages::$messages.
     *  3 - array for renaming fields in the answer, initialized as Messages::$filedNames.
     *  4 - checker class with variables and methods to validate, initialized as Checker::class.
     *  5 - callback class and static method to send validate error on callback, initialized as ['Validator\\Validony', 'AnswerErrorCallback'].
     *  6 - language to find messages array in $messages and $fieldNames.
     *
     * $validator = (new Validony($_POST, \Validator\Messages::$messages, \Validator\Messages::$filedNames, Checker::class, ['Validator\\Validony', 'AnswerErrorCallback'], 'en'));
     *
     * Call Method:
     * Params:
     *  1 - $fields - array of rules.
     *  2 - $CallBack - callback function for returning an error message if no valid field is found (true/false).
     *  3 - $printField - include field names in the error message (true/false).
     *  4 - $printData - include field values in the error message (true/false).
     *  5 - $getAllErrors - return all founded errors after validating all fields (true/false).
     *
     * $validator->CheckData($fields, false, true, false, false);
     *
     * Check IsValid:
     * $valid = $validator->isValid();
     *
     * Get Errors:
     * Params:
     *  1 - $string - use to return errors as a string or array.
     *  2 - $getFields - use to get fields which called error on validation process.
     *      if 1 - false, 2 - true: returns an associative array with errors and field names.
     *
     * $errors = $validator->getErrors(false, true);
     *
     * @return bool|void
     */
    public function CheckData(array $fields, mixed $CallBack = false, mixed $printField = true, mixed $printData = false, bool $getAllErrors = false)
    {
        try {
            foreach ($fields as $field => $params) {
                ///Required check
                if (in_array($this->checkerClass::required, $params, true) !== false && !isset($this->data[$field])) {
                    $err = $this->buildMessageProcess($field, $printField, $printData, $CallBack, $getAllErrors, 'required');
                    if ($err === false)
                    {
                        return false;
                    }
                }
                ///Fields
                if (isset($this->data[$field])) {
                    foreach ($params as $param) {
                        if (!in_array($param, Validony::$skip_fields_regex)) {
                            if (method_exists($this->checkerClass, $param)) {
                                if (!$this->checkerClass::$param($this->data[$field])) {
                                    $err = $this->buildMessageProcess($field, $printField, $printData, $CallBack, $getAllErrors);
                                    if ($err === false)
                                    {
                                        return false;
                                    }
                                }
                            } else {
                                $err = $this->buildMessageProcess($field, $printField, $printData, $CallBack, $getAllErrors, 'method');
                                if ($err === false)
                                {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            die();
        }

        return !($this->errors !== []);
    }


    /**
     * @param $field
     * @param $printField
     * @param $printData
     * @param $CallBack
     * @param $getAllErrors
     * @param string $way
     * @return bool
     *
     *  Check what should be returned as answer when filed\fields no valid and returns it.
     */

    public function buildMessageProcess($field, $printField, $printData, $CallBack, $getAllErrors, string $way = 'field'): bool
    {
        $data = $this->data[$field] ?? null;
        $error = Messages::getMessageField($field, $data, $printField, $printData, $this->err_language, $way, $this->customMessagesMass, $this->customFieldName);
        $this->errors[] = $error;
        $this->error_fields[] = $field;
        if ($CallBack)
        {
            $this->sendError($error);
        }
        if (!$getAllErrors)
        {
            return false;
        }
        return true;
    }


    /**
     * Validate a set of fields based on specified rules.
     *
     * @param array $fields
     *
     * Allow to check a couple fields with one rule.
     *
     * Example:
     * Create array with rules:
     *   $fields = [
     *      'bank' => [C::type]  // The method will check 'bank1', 'bank_new', 'bank2', and other fields in POST that start with 'bank'
     *   ];
     *
     * Create class:
     * Params:
     *  1 - post array with data to validate.
     *  2 - error messages array, initialized as \Validator\Messages::$messages.
     *  3 - array for renaming fields in the answer, initialized as Messages::$filedNames.
     *  4 - checker class with variables and methods to validate, initialized as Checker::class.
     *  5 - callback class and static method to send validate error on callback, initialized as ['Validator\\Validony', 'AnswerErrorCallback'].
     *  6 - language to find messages array in $messages and $fieldNames.
     *
     * $validator = (new Validony($_POST, \Validator\Messages::$messages, \Validator\Messages::$filedNames, Checker::class, ['Validator\\Validony', 'AnswerErrorCallback'], 'en'));
     *
     * Call Method:
     * Params:
     *  1 - $fields - array of rules.
     *  2 - $callback - call function for returning an error message if no valid field is found - true/false.
     *  3 - $printField - add field name to the error message - true/false.
     *  4 - $printData - add field's value to the error message - true/false.
     *  5 - $getAllErrors - return all founded errors after validating all fields - true/false.
     *
     * It will take each key from $fields and validate with its rules
     * all keys from the $POST array which start as $fields[key].
     * $validator->CheckLikeFieldsData($fields, false, true, true, true);
     *
     * Check IsValid:
     * $valid = $validator->isValid();
     *
     * Get Errors:
     * Params:
     *  1 - $string - use to return errors as a string or array.
     *  2 - $getFields - use to get fields which called error on the validation process.
     *      if 1 - false, 2 - true: returns an associative array with errors and field names.
     *
     * $errors = $validator->getErrors(false, true);
     */

    public function CheckLikeFieldsData(array $fields, bool $CallBack = false, bool $printField = true, $printData = false, $getAllErrors = false): bool
    {
        foreach ($fields as $rK => $rV)
        {
            foreach ($this->data as $ky => $vl)
            {
                if (stripos($ky, $rK) === 0)
                {
                    $this->CheckData([$ky => $rV], $CallBack, $printField, $printData, $getAllErrors);
                }
            }
        }
        return true;
    }


    /// Find classes and needed methods
    public function getClassAndMethodForList($pathOfLists, $namespaceOfListsClasses): array
    {
        $dir = $pathOfLists === false ? __DIR__.'/Lists/' : $pathOfLists;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        $regex    = new RegexIterator($iterator, '/^.+\.php$/i', RegexIterator::GET_MATCH);
        $classes = [];
        foreach ($regex as $f) {
            $res = self::parseTokens($f[0], $namespaceOfListsClasses);
            if ($res !== [])
            {
                $classes[] = $res;
            }
        }
        return $classes;
    }

    /// Set filename and namespace
    private function parseTokens($file, $namespaceOfListsClasses): array
    {
//        Ajax::AnswerError(__NAMESPACE__.'\\Lists\\');
        $f = basename($file, ".php");
        $tokens     = token_get_all(file_get_contents($file));
        $namespace = $namespaceOfListsClasses !== false ? $namespaceOfListsClasses : __NAMESPACE__.'\\Lists\\';
        foreach ($tokens as $token) {
            switch ($token[0]) {
                case T_CLASS:
                case T_NAMESPACE:
                case T_STRING:
                    return ['file'=>$f, 'ns' => $namespace];
                    break;
            }
        }
        return [];
    }

}