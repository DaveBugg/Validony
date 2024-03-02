<?php


namespace DavesValidator\Validator;

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
    public static function AnswerErrorCallback($MSG, $case = 1)
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

    public function sendError($msg, $type = 1)
    {
        $class = $this->classCallback;
        $method = $this->methodCallback;
        $this->customErrorSendMethod
            ? $class::$method($msg)
            : $class::$method($msg, $type);
    }

    /**
     * @param string $method
     * @param bool|string $pathOfLists
     * @param bool $callback
     * @param bool $printField
     * @param bool $printData
     * @param bool $getAllErrors
     *
     * Example:
     *
     * Create class:
     * Params: 1 - post array with data to validate. 2 - error messages array, init - \Validator\Messages::$messages
     *         3 - array for rename fields in answer, init - Messages::$filedNames,
     *         4 - checker class with vars and methods to validate, default - Checker::class
     *         5 - callback class and static method to send validate error on callback, init - ['Validator\\Validony', 'AnswerErrorCallback']
     *         6 - language to find messages array in $messages and $fieldNames.
     *
     *         $validator = (new Validony($_POST, \Validator\Messages::$messages, \Validator\Messages::$filedNames , Checker::class, ['Validator\\Validony', 'AnswerErrorCallback'], 'en'));
     *
     * Call Method:
     * Params: 1 - Static method for validation (return array with rules),
     *             ValidateList tries to find the method you type in all classes in folder /Lists/ which locates in lib main path.
     *             You can choose your own path in param 2.
     *         2 - path for your classes for Lists, init - false (use Lists in lib path)
     *         3 - namespace of your classes locates in Lists folder, init - false (use __NAMESPACE__.'\\Lists\\' )
     *         4 - $callback - call function for return error message if no valid filed found - true/false
     *         5 - $printField - for add filed name to error message
     *         6 - $printData - for add field's value to error message
     *         7 - $getAllErrors - returns all founded errors after validate all field
     *
     *         $validator->ValidateList('TimeValidator', false, false, true, true, true);
     *
     * Check IsValid: $valid = $validator->isValid();
     *
     * Get Errors:
     * Params: 1 - $string - use to return errors as string or array
     *         2 - $getFields - use to get fields which called error on validation process
     *         if 1 - false, 2 -true : returns associative mass with errors and fields names
     *
     *              $errors = $validator->getErrors(false, true);
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
     * @param array $fields
     * @param mixed $CallBack
     * @param mixed $printField
     * @param mixed $printData
     * @param bool $getAllErrors
     *
     * Example:
     *
     * Create rules mass:
     *          $fields = [
     *               'time' =>  [Checker::required, Checker::time],
     *               'test' =>  [Checker::checkBoxOnOff], //- string var with name of static method in your class Checker
     *               'name' => ['required', 'name'] //- static method with the same name should be exist in your class Checker
     *         ];
     *
     * Create class:
     * Params: 1 - post array with data to validate. 2 - error messages array, init - \Validator\Messages::$messages
     *         3 - array for rename fields in answer, init - Messages::$filedNames,
     *         4 - checker class with vars and methods to validate, init - Checker::class
     *         5 - callback class and static method to send validate error on callback, init - ['Validator\\Validony', 'AnswerErrorCallback']
     *         6 - language to find messages array in $messages and $fieldNames.
     *
     *         $validator = (new Validony($_POST, \Validator\Messages::$messages, \Validator\Messages::$filedNames , Checker::class, ['Validator\\Validony', 'AnswerErrorCallback'], 'en'));
     *
     * Call Method:
     * Params: 1 - $fields - array of rules
     *         2 - $callback - call function for return error message if no valid filed found - true/false
     *         3 - $printField - for add filed name to error message - true/false
     *         4 - $printData - for add field's value to error message - true/false
     *         5 - $getAllErrors - returns all founded errors after validate all field - true/false
     *
     *         $validator->CheckData($init, false, true, false, false);
     *
     * Check IsValid: $valid = $validator->isValid();
     *
     * Get Errors:
     * Params: 1 - $string - use to return errors as string or array
     *         2 - $getFields - use to get fields which called error on validation process
     *         if 1 - false, 2 -true : returns associative mass with errors and fields names
     *
     *         $errors = $validator->getErrors(false, true);
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

    public function buildMessageProcess($field, $printField, $printData, $CallBack, $getAllErrors, $way = 'field')
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
     * @param array $fields
     *
     * Allow to check a couple fields with one rule
     * Example:
     * Create array with rules:
     *   $fields = [
     *          'bank' => [C::type]  //method will check 'bank1', 'bank_new', 'bank2' and other exist in POST fields starts with 'bank'
     *    ];
     *
     * Create class:
     * Params: 1 - post array with data to validate. 2 - error messages array, init - \Validator\Messages::$messages
     *         3 - array for rename fields in answer, init - Messages::$filedNames,
     *         4 - checker class with vars and methods to validate, init - Checker::class
     *         5 - callback class and static method to send validate error on callback, init - ['Validator\\Validony', 'AnswerErrorCallback']
     *         6 - language to find messages array in $messages and $fieldNames.
     *
     *         $validator = (new Validony($_POST, \Validator\Messages::$messages, \Validator\Messages::$filedNames , Checker::class, ['Validator\\Validony', 'AnswerErrorCallback'], 'en'));
     *
     * Call Method:
     * Params: 1 - $fields - array of rules
     *         2 - $callback - call function for return error message if no valid filed found - true/false
     *         3 - $printField - for add filed name to error message - true/false
     *         4 - $printData - for add field's value to error message - true/false
     *         5 - $getAllErrors - returns all founded errors after validate all field - true/false
     *
     *         It will take each key from $fields and validate with it's rules
     *         all keys from $POST array which starts as $fields[key]
     *         $validator->CheckLikeFieldsData($fields, false, true, true, true);
     *
     * Check IsValid:
     *         $valid = $validator->isValid();
     *
     * Get Errors:
     * Params: 1 - $string - use to return errors as string or array
     *         2 - $getFields - use to get fields which called error on validation process
     *         if 1 - false, 2 -true : returns associative mass with errors and fields names
     *
     *         $errors = $validator->getErrors(false, true);
     *
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