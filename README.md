# Validony
## Simply and powerful data validator
The project provides a tool for data validation.
Possibilities:
- Array validation
- Array validation with similar keys
## Composer
`composer require davebugg/validony`
## Usage
### Dynamic
Define class and set it up
```php
$validator = (new Validony(
$_POST,                                             // Array to validate 
\DavesValidator\Messages::$messages,      // Array with error messages
\DavesValidator\Messages::$filedNames,    // Array to rename fields in answer
\DavesValidator\Checker::class,           // Class which contains validation methods
['DavesValidator\\Validony', 'AnswerErrorCallback'], // Class and static method to send validation error
'en'));// Language for errors (the keys of ...\Messages::$messages or your Class for messages)
```
Call it
```php
//in Lists folder, needs to return an array for check
$validator->ValidateList(
'TimeValidator', //Method to return the validation rules 
false, // Path to your Lists Directory
'DavesValidator\\Lists\\', // Namespace of your classes contains in Lists Folder 
false, // Run Callback functions if found\fields with no valid data
true, // Print field's name in error message
true, // Print field's value in error message
false); // Return all errors in one iteration
```
Get result and errors
```php
$valid = $validator->isValid(); // valid or not
$errors = $validator->getErrors(
false, // return string || array
true); // return array of fields in errors array if true
```
Validation for an array with similar keys:
```php
$init = [ 
    'password' =>  [C::required, C::password] // Rules
    ];
$_POST = [                                   // Data
    'password_1' => '42',
    'password2' => '42',
    'password_abcd' => 'abcd'
];
$validatorLists = (new ValidateLists($_POST));
$validatorLists->CheckData($init,false,true,true,true);
$valid = $validatorLists->isValid();
$errors = $validatorLists->getErrors(false, true);
```
### Static
The static class is less appealing to use, but you can use it by referring to the PHPDoc inside the class.
### Additional
Explore the Checker and MainLists classes for a more detailed understanding.
