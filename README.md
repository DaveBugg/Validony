# Validony

## Powerful and Flexible PHP Data Validator

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.0-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

Validony is a modern PHP library for data validation that provides flexible capabilities for validating data arrays with customizable rules, error messages, and callback functions.

## 🚀 Features

- ✅ **Array data validation** with customizable rules
- ✅ **Similar field name validation** (e.g., `password_1`, `password_2`)
- ✅ **Flexible error message system** with multi-language support
- ✅ **Customizable callback functions** for error handling
- ✅ **Static and dynamic methods** for usage
- ✅ **Support for custom validation classes**
- ✅ **Get all errors or stop on first error**

> **⚠️ IMPORTANT:** The default `Checker`, `Messages`, and `Lists` classes included with this library are **examples only**. For production applications, you should create your own custom classes with validation rules, error messages, and field names specific to your application's needs.

## 📦 Installation

```bash
composer require davebugg/validony
```

## 🔧 Quick Start

### Basic Usage

```php
use DavesValidator\Validator\Validony;
use DavesValidator\Validator\Checker;

// Create validator with basic settings
$validator = new Validony($_POST);

// Define validation rules
$rules = [
    'email' => [Checker::required, Checker::email],
    'password' => [Checker::required, Checker::password],
    'age' => [Checker::numeric]
];

// Perform validation
$validator->CheckData($rules);

// Check result
if ($validator->isValid()) {
    echo "Data is valid!";
} else {
    $errors = $validator->getErrors();
    print_r($errors);
}
```

## 🏗️ Custom Classes - **RECOMMENDED APPROACH**

**⚠️ Important:** For production applications, it's **highly recommended** to create your own custom classes instead of using the default ones. This gives you full control over validation logic, error messages, and field names.

### 1. Custom Checker Class

Create your own validation methods class based on the default `Checker` class:

```php
// app/Validators/MyChecker.php
namespace App\Validators;

class MyChecker
{
    // Define constants for your validation rules
    public const required = 'required';
    public const email = 'email';
    public const password = 'password';
    public const username = 'username';
    public const age = 'age';
    public const phone = 'phone';
    
    // Custom validation methods
    public static function required($val): bool
    {
        return !empty($val) && $val !== null && $val !== '';
    }
    
    public static function email($val): bool
    {
        return filter_var($val, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function password($val): bool
    {
        // Strong password: min 8 chars, uppercase, lowercase, number, special char
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $val);
    }
    
    public static function username($val): bool
    {
        // Username: 3-20 chars, letters, numbers, underscore
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $val);
    }
    
    public static function age($val): bool
    {
        return is_numeric($val) && $val >= 13 && $val <= 120;
    }
    
    public static function phone($val): bool
    {
        // International phone format
        return preg_match('/^\+?[1-9]\d{1,14}$/', $val);
    }
    
    // Add more custom validation methods as needed
    public static function zipCode($val): bool
    {
        return preg_match('/^\d{5}(-\d{4})?$/', $val); // US ZIP code
    }
    
    public static function creditCard($val): bool
    {
        // Basic credit card validation (Luhn algorithm)
        $val = preg_replace('/\D/', '', $val);
        return strlen($val) >= 13 && strlen($val) <= 19;
    }
}
```

### 2. Custom Messages Class

Create your own messages class for multilingual error messages:

```php
// app/Validators/MyMessages.php
namespace App\Validators;

class MyMessages
{
    public static array $messages = [
        'en' => [
            'required' => ['The ', ' field is required'],
            'email' => ['The ', ' must be a valid email address'],
            'password' => ['The ', ' must be at least 8 characters long'],
            'numeric' => ['The ', ' must be a number'],
            'minLength' => ['The ', ' must be at least :min characters long'],
            'maxLength' => ['The ', ' must not exceed :max characters']
        ],
        'es' => [
            'required' => ['El campo ', ' es obligatorio'],
            'email' => ['El ', ' debe ser una dirección de email válida'],
            'password' => ['La ', ' debe tener al menos 8 caracteres'],
            'numeric' => ['El ', ' debe ser un número'],
            'minLength' => ['El ', ' debe tener al menos :min caracteres'],
            'maxLength' => ['El ', ' no debe exceder :max caracteres']
        ],
        'fr' => [
            'required' => ['Le champ ', ' est requis'],
            'email' => ['Le ', ' doit être une adresse email valide'],
            'password' => ['Le ', ' doit contenir au moins 8 caractères'],
            'numeric' => ['Le ', ' doit être un nombre'],
            'minLength' => ['Le ', ' doit contenir au moins :min caractères'],
            'maxLength' => ['Le ', ' ne doit pas dépasser :max caractères']
        ],
        'ru' => [
            'required' => ['Поле ', ' обязательно для заполнения'],
            'email' => ['Поле ', ' должно содержать корректный email адрес'],
            'password' => ['Поле ', ' должно содержать минимум 8 символов'],
            'numeric' => ['Поле ', ' должно быть числом'],
            'minLength' => ['Поле ', ' должно содержать минимум :min символов'],
            'maxLength' => ['Поле ', ' не должно превышать :max символов']
        ]
    ];

    public static array $fieldNames = [
        'en' => [
            'email' => 'Email Address',
            'password' => 'Password',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone Number'
        ],
        'es' => [
            'email' => 'Dirección de Email',
            'password' => 'Contraseña',
            'username' => 'Nombre de Usuario',
            'first_name' => 'Nombre',
            'last_name' => 'Apellido',
            'phone' => 'Número de Teléfono'
        ],
        'fr' => [
            'email' => 'Adresse Email',
            'password' => 'Mot de passe',
            'username' => 'Nom d\'utilisateur',
            'first_name' => 'Prénom',
            'last_name' => 'Nom de famille',
            'phone' => 'Numéro de téléphone'
        ],
        'ru' => [
            'email' => 'Email адрес',
            'password' => 'Пароль',
            'username' => 'Имя пользователя',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Номер телефона'
        ]
    ];
}
```

### 3. Custom Lists Classes

Create organized validation rule sets for different forms:

```php
// app/Validators/Lists/UserValidation.php
namespace App\Validators\Lists;

use App\Validators\MyChecker;

class UserValidation
{
    public static function registration(): array
    {
        return [
            'username' => [MyChecker::required, MyChecker::username],
            'email' => [MyChecker::required, MyChecker::email],
            'password' => [MyChecker::required, MyChecker::password],
            'age' => [MyChecker::required, MyChecker::age],
            'phone' => [MyChecker::phone] // Optional field
        ];
    }
    
    public static function login(): array
    {
        return [
            'email' => [MyChecker::required, MyChecker::email],
            'password' => [MyChecker::required]
        ];
    }
    
    public static function profile(): array
    {
        return [
            'first_name' => [MyChecker::required],
            'last_name' => [MyChecker::required],
            'email' => [MyChecker::required, MyChecker::email],
            'phone' => [MyChecker::phone],
            'age' => [MyChecker::age]
        ];
    }
}

// app/Validators/Lists/OrderValidation.php
namespace App\Validators\Lists;

use App\Validators\MyChecker;

class OrderValidation
{
    public static function checkout(): array
    {
        return [
            'email' => [MyChecker::required, MyChecker::email],
            'phone' => [MyChecker::required, MyChecker::phone],
            'zipCode' => [MyChecker::required, MyChecker::zipCode],
            'creditCard' => [MyChecker::required, MyChecker::creditCard]
        ];
    }
    
    public static function shipping(): array
    {
        return [
            'first_name' => [MyChecker::required],
            'last_name' => [MyChecker::required],
            'address' => [MyChecker::required],
            'city' => [MyChecker::required],
            'zipCode' => [MyChecker::required, MyChecker::zipCode]
        ];
    }
}
```

### 4. Using Custom Classes

Now use your custom classes with Validony:

```php
use DavesValidator\Validator\Validony;
use App\Validators\MyChecker;
use App\Validators\MyMessages;

// Method 1: Direct validation with custom classes
$validator = new Validony(
    $_POST,                           // Data to validate
    MyMessages::$messages,            // Your custom messages
    MyMessages::$fieldNames,          // Your custom field names
    MyChecker::class,                 // Your custom checker class
    [],                               // Callback (optional)
    'en',                             // Language
    true,                             // Show field names
    false,                            // Don't show values
    true,                             // Get all errors
    false                             // Manual error handling
);

// Define rules using your custom checker
$rules = [
    'username' => [MyChecker::required, MyChecker::username],
    'email' => [MyChecker::required, MyChecker::email],
    'password' => [MyChecker::required, MyChecker::password],
    'age' => [MyChecker::required, MyChecker::age]
];

$validator->CheckData($rules);

if ($validator->isValid()) {
    echo "Registration successful!";
} else {
    $errors = $validator->getErrors(true);
    foreach ($errors['errors'] as $error) {
        echo $error . "\n";
    }
}

// Method 2: Using validation lists with custom path and namespace
$validator = new Validony(
    $_POST,
    MyMessages::$messages,
    MyMessages::$fieldNames,
    MyChecker::class,
    [],
    'en',
    true,
    false,
    true,
    false
);

$validator->ValidateList(
    'registration',                    // Method name
    'app/Validators/Lists/',          // Path to your Lists folder
    'App\\Validators\\Lists\\'        // Namespace of your Lists classes
);

if ($validator->isValid()) {
    echo "User registration is valid!";
} else {
    $errors = $validator->getErrors();
    print_r($errors);
}
```

### 5. Complete Example with Custom Classes

```php
// Complete registration form validation example
use DavesValidator\Validator\Validony;
use App\Validators\MyChecker;
use App\Validators\MyMessages;

class RegistrationController
{
    public function register()
    {
        // Custom error handler
        $errorHandler = function($message) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $message
            ]);
            exit;
        };

        // Create validator with all custom classes
        $validator = new Validony(
            $_POST,                           // Form data
            MyMessages::$messages,            // Custom error messages
            MyMessages::$fieldNames,          // Custom field names
            MyChecker::class,                 // Custom validation methods
            [$this, 'handleValidationError'], // Custom callback
            'en',                             // Language
            true,                             // Include field names in errors
            false,                            // Don't include values (security)
            true,                             // Collect all errors
            false                             // Handle errors manually
        );

        // Use validation list for registration
        $validator->ValidateList(
            'registration',
            'app/Validators/Lists/',
            'App\\Validators\\Lists\\'
        );

        if ($validator->isValid()) {
            // Process registration
            $this->createUser($_POST);
            
            echo json_encode([
                'success' => true,
                'message' => 'Registration successful!'
            ]);
        } else {
            $errors = $validator->getErrors(true);
            
            echo json_encode([
                'success' => false,
                'errors' => $errors['errors'],
                'fields' => $errors['fields']
            ]);
        }
    }
    
    public function handleValidationError($message)
    {
        // Log validation error
        error_log("Validation failed: " . $message);
        
        // You can add additional error handling here
        // For example, send to monitoring service
    }
    
    private function createUser($data)
    {
        // Your user creation logic here
    }
}
```

## 🛠 Constructor and Settings

### Constructor Parameters

```php
public function __construct(
    array $post,                        // Data to validate
    array|bool $customMessagesMass = false,  // Custom error messages
    array|bool $customFieldName = false,     // Custom field names
    mixed $checkerClass = false,             // Validation methods class
    array $callback = [],                    // Error handling callback
    string $errLanguage = 'en',              // Error message language
    bool $printField = true,                 // Include field name in message
    bool $printData = false,                 // Include field value in message
    bool $getAllErrors = false,              // Collect all errors or stop on first
    bool $doCallback = false                 // Call callback on error
)
```

### Detailed Parameter Description

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$post` | `array` | - | **Required.** Data array to validate (usually `$_POST`) |
| `$customMessagesMass` | `array\|bool` | `false` | Custom error messages array |
| `$customFieldName` | `array\|bool` | `false` | Array for renaming fields in messages |
| `$checkerClass` | `mixed` | `Checker::class` | Class containing validation methods |
| `$callback` | `array` | `[]` | Array `[class, method]` for callback function |
| `$errLanguage` | `string` | `'en'` | Language for error messages |
| `$printField` | `bool` | `true` | Include field name in error message |
| `$printData` | `bool` | `false` | Include field value in error message |
| `$getAllErrors` | `bool` | `false` | Collect all errors (`true`) or stop on first (`false`) |
| `$doCallback` | `bool` | `false` | Automatically call callback when error is found |

## 📋 Validation Methods

### 1. CheckData() - Main Validation

Validates data according to specified rules.

```php
public function CheckData(
    array $fields,              // Validation rules
    mixed $CallBack = null,     // Override callback (null = use constructor setting)
    mixed $printField = null,   // Override printField
    mixed $printData = null,    // Override printData
    bool|null $getAllErrors = null  // Override getAllErrors
)
```

**Example:**

```php
$validator = new Validony($_POST, false, false, false, [], 'en', true, false, true, false);

$rules = [
    'username' => [Checker::required, Checker::minLength],
    'email' => [Checker::required, Checker::email],
    'password' => [Checker::required, Checker::password]
];

// Use constructor settings
$validator->CheckData($rules);

// Override settings for specific call
$validator->CheckData($rules, true, false, true, true); // enable callback and getAllErrors
```

### 2. ValidateList() - Validation via Rule Lists

Uses predefined rule lists from classes in the Lists folder.

**⚠️ Important:** The `ValidateList` method will use the `Checker` class specified in the constructor. If you pass a custom `$checkerClass` to the constructor, your Lists classes should reference that custom checker, not the default `Checker` class.

```php
public function ValidateList(
    string $method,                           // Method name returning rules
    bool|string $pathOfLists = false,         // Path to Lists folder
    bool|string $namespaceOfListsClasses = false, // Namespace of Lists classes
    bool|null $callback = null,               // Override callback
    bool|null $printField = null,             // Override printField
    bool|null $printData = null,              // Override printData
    bool|null $getAllErrors = null            // Override getAllErrors
)
```

**Example with Default Checker:**

```php
// Using default Checker class from the library
use DavesValidator\Validator\Checker;

// Lists/UserValidator.php (using default Checker)
class UserValidator {
    public static function registrationRules(): array {
        return [
            'username' => [Checker::required, Checker::login], // Uses default Checker
            'email' => [Checker::required, Checker::email],
            'password' => [Checker::required, Checker::password],
            'confirm_password' => [Checker::required]
        ];
    }
}

// Usage with default Checker
$validator = new Validony($_POST); // Uses default Checker::class
$validator->ValidateList('registrationRules');

if ($validator->isValid()) {
    echo "Registration successful!";
}
```

**Example with Custom Checker (RECOMMENDED):**

```php
// Using your custom Checker class
use App\Validators\MyChecker;

// app/Validators/Lists/UserValidator.php (using custom Checker)
class UserValidator {
    public static function registrationRules(): array {
        return [
            'username' => [MyChecker::required, MyChecker::username], // Uses YOUR custom Checker
            'email' => [MyChecker::required, MyChecker::email],
            'password' => [MyChecker::required, MyChecker::password],
            'confirm_password' => [MyChecker::required]
        ];
    }
}

// Usage with custom Checker
$validator = new Validony(
    $_POST,
    MyMessages::$messages,
    MyMessages::$fieldNames,
    MyChecker::class,  // ← This tells Validony to use YOUR custom Checker
    [],
    'en'
);

$validator->ValidateList(
    'registrationRules',
    'app/Validators/Lists/',      // Path to your Lists folder
    'App\\Validators\\Lists\\'   // Namespace of your Lists classes
);

if ($validator->isValid()) {
    echo "Registration successful!";
}
```

**How it works:**
1. Validony looks for the specified method (`registrationRules`) in classes within the Lists folder
2. The method returns an array of validation rules
3. Each rule references methods from the Checker class specified in the constructor
4. If you use `MyChecker::class` in constructor, your Lists should use `MyChecker::methodName`
5. If you use default `Checker::class` (or `false`), your Lists should use `Checker::methodName`

### 3. CheckLikeFieldsData() - Similar Fields Validation

Validates fields whose names start with a specific prefix.

```php
public function CheckLikeFieldsData(
    array $fields,                  // Rules for field prefixes
    bool|null $CallBack = null,     // Override callback
    bool|null $printField = null,   // Override printField
    bool|null $printData = null,    // Override printData
    bool|null $getAllErrors = null  // Override getAllErrors
)
```

**Example:**

```php
// Data
$_POST = [
    'password_1' => 'secret123',
    'password_2' => 'secret456',
    'password_new' => 'newsecret',
    'email' => 'test@example.com'
];

// Rules for fields starting with 'password'
$rules = [
    'password' => [Checker::required, Checker::password]
];

$validator = new Validony($_POST);
$validator->CheckLikeFieldsData($rules);

// Will check password_1, password_2, password_new
```

## 📤 Getting Results

### isValid() - Check Validity

```php
$isValid = $validator->isValid(); // true/false
```

### getErrors() - Get Errors

```php
public function getErrors(bool $getFields = false): array
```

**Parameters:**
- `$getFields` - if `true`, also returns field names with errors

**Examples:**

```php
// Errors only
$errors = $validator->getErrors();
// Result: ['errors' => ['Email is invalid', 'Password is required']]

// Errors with field names
$errorsWithFields = $validator->getErrors(true);
// Result: [
//     'errors' => ['Email is invalid', 'Password is required'],
//     'fields' => ['email', 'password']
// ]
```

## 🎯 Static Methods

For quick usage without creating class instance.

### Validon::CheckData()

```php
use DavesValidator\Validator\Validon;

[$isValid, $errors] = Validon::CheckData(
    $_POST,                    // Data
    $rules,                    // Rules
    false,                     // Custom messages
    false,                     // Custom field names
    false,                     // Checker class
    [],                        // Callback
    false,                     // Call callback
    'en',                      // Language
    true,                      // Print field name
    false,                     // Print value
    false,                     // All errors
    false                      // Get fields with errors
);
```

### Validon::ValidateList()

```php
[$isValid, $errors] = Validon::ValidateList(
    $_POST,                    // Data
    'registrationRules',       // Method with rules
    false,                     // Custom messages
    false,                     // Custom field names
    false,                     // Checker class
    [],                        // Callback
    false,                     // Call callback
    'en',                      // Language
    false,                     // Path to Lists
    false,                     // Namespace Lists
    true,                      // Print field name
    false,                     // Print value
    false,                     // All errors
    false                      // Get fields with errors
);
```

## 🔧 Configuration Examples

### Minimal Setup (NOT RECOMMENDED for production)

```php
// Uses default library classes - only for testing/development
$validator = new Validony($_POST);
$validator->CheckData($rules);
```

### Recommended Production Setup

```php
// Use your own custom classes for production
$validator = new Validony(
    $_POST,                           // Data
    MyMessages::$messages,            // Your custom messages
    MyMessages::$fieldNames,          // Your custom field names
    MyChecker::class,                 // Your custom checker
    [Logger::class, 'log'],           // Error logging
    'en',                             // Language
    true,                             // Show field names
    false,                            // Hide values (security)
    true,                             // Collect all errors
    false                             // Manual error handling
);
```

### Development Setup with Debugging

```php
// Development setup with detailed debugging
$validator = new Validony(
    $_POST,                           // Data
    MyMessages::$messages,            // Your custom messages (even in dev)
    MyMessages::$fieldNames,          // Your custom field names
    MyChecker::class,                 // Your custom checker
    [Debug::class, 'dump'],           // Debug callback
    'en',                             // Language
    true,                             // Show field names
    true,                             // Show values (debugging)
    true,                             // Collect all errors
    true                              // Auto callback
);
```

### Multi-Environment Configuration

```php
class ValidatorFactory
{
    public static function create($data, $language = 'en')
    {
        $isProduction = $_ENV['APP_ENV'] === 'production';
        
        return new Validony(
            $data,
            MyMessages::$messages,        // Always use custom messages
            MyMessages::$fieldNames,      // Always use custom field names
            MyChecker::class,             // Always use custom checker
            $isProduction 
                ? [Logger::class, 'logError']     // Production: log errors
                : [Debug::class, 'dumpError'],    // Development: dump errors
            $language,
            true,                         // Always show field names
            !$isProduction,               // Show values only in development
            true,                         // Always collect all errors
            $isProduction                 // Auto-callback in production only
        );
    }
}

// Usage
$validator = ValidatorFactory::create($_POST, 'en');
$validator->CheckData($rules);
```

## 🔧 Advanced Examples

### Example 1: Complete Registration System

```php
use DavesValidator\Validator\Validony;
use App\Validators\MyChecker;
use App\Validators\MyMessages;

class RegistrationController
{
    public function register()
    {
        // Create validator with all custom classes
        $validator = new Validony(
            $_POST,                           // Form data
            MyMessages::$messages,            // Custom error messages
            MyMessages::$fieldNames,          // Custom field names
            MyChecker::class,                 // Custom validation methods
            [$this, 'handleValidationError'], // Custom callback
            'en',                             // Language
            true,                             // Include field names in errors
            false,                            // Don't include values (security)
            true,                             // Collect all errors
            false                             // Handle errors manually
        );

        // Use validation list for registration
        $validator->ValidateList(
            'registration',
            'app/Validators/Lists/',
            'App\\Validators\\Lists\\'
        );

        if ($validator->isValid()) {
            // Process registration
            $this->createUser($_POST);
            
            echo json_encode([
                'success' => true,
                'message' => 'Registration successful!'
            ]);
        } else {
            $errors = $validator->getErrors(true);
            
            echo json_encode([
                'success' => false,
                'errors' => $errors['errors'],
                'fields' => $errors['fields']
            ]);
        }
    }
    
    public function handleValidationError($message)
    {
        // Log validation error
        error_log("Validation failed: " . $message);
    }
    
    private function createUser($data)
    {
        // Your user creation logic here
    }
}
```

### Example 2: Multiple Fields Validation

```php
// Form data with multiple similar fields
$_POST = [
    'product_name_1' => 'Product 1',
    'product_name_2' => 'Product 2',
    'product_price_1' => '100',
    'product_price_2' => '200',
    'product_description_1' => 'Product 1 description',
    'product_description_2' => 'Product 2 description'
];

$validator = new Validony(
    $_POST, 
    MyMessages::$messages, 
    MyMessages::$fieldNames, 
    MyChecker::class, 
    [], 
    'en', 
    true, 
    false, 
    true
);

// Rules for all fields starting with specific prefixes
$rules = [
    'product_name' => [MyChecker::required, MyChecker::minLength],
    'product_price' => [MyChecker::required, MyChecker::numeric],
    'product_description' => [MyChecker::required]
];

$validator->CheckLikeFieldsData($rules);

if ($validator->isValid()) {
    echo "All products are valid!";
} else {
    $errors = $validator->getErrors(true);
    echo "Errors found in fields: " . implode(', ', $errors['fields']);
}
```

## 🌐 Multi-language Support

### Using Your Custom Messages Class (RECOMMENDED)

The best approach is to create your own Messages class with all the languages and messages you need:

```php
// app/Validators/MyMessages.php
namespace App\Validators;

class MyMessages
{
    public static array $messages = [
        'en' => [
            'required' => ['The ', ' field is required'],
            'email' => ['The ', ' must be a valid email address'],
            'password' => ['The ', ' must be at least 8 characters long'],
            'numeric' => ['The ', ' must be a number'],
            'minLength' => ['The ', ' must be at least :min characters long'],
            'maxLength' => ['The ', ' must not exceed :max characters']
        ],
        'es' => [
            'required' => ['El campo ', ' es obligatorio'],
            'email' => ['El ', ' debe ser una dirección de email válida'],
            'password' => ['La ', ' debe tener al menos 8 caracteres'],
            'numeric' => ['El ', ' debe ser un número'],
            'minLength' => ['El ', ' debe tener al menos :min caracteres'],
            'maxLength' => ['El ', ' no debe exceder :max caracteres']
        ],
        'fr' => [
            'required' => ['Le champ ', ' est requis'],
            'email' => ['Le ', ' doit être une adresse email valide'],
            'password' => ['Le ', ' doit contenir au moins 8 caractères'],
            'numeric' => ['Le ', ' doit être un nombre'],
            'minLength' => ['Le ', ' doit contenir au moins :min caractères'],
            'maxLength' => ['Le ', ' ne doit pas dépasser :max caractères']
        ],
        'ru' => [
            'required' => ['Поле ', ' обязательно для заполнения'],
            'email' => ['Поле ', ' должно содержать корректный email адрес'],
            'password' => ['Поле ', ' должно содержать минимум 8 символов'],
            'numeric' => ['Поле ', ' должно быть числом'],
            'minLength' => ['Поле ', ' должно содержать минимум :min символов'],
            'maxLength' => ['Поле ', ' не должно превышать :max символов']
        ]
    ];

    public static array $fieldNames = [
        'en' => [
            'email' => 'Email Address',
            'password' => 'Password',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone Number'
        ],
        'es' => [
            'email' => 'Dirección de Email',
            'password' => 'Contraseña',
            'username' => 'Nombre de Usuario',
            'first_name' => 'Nombre',
            'last_name' => 'Apellido',
            'phone' => 'Número de Teléfono'
        ],
        'fr' => [
            'email' => 'Adresse Email',
            'password' => 'Mot de passe',
            'username' => 'Nom d\'utilisateur',
            'first_name' => 'Prénom',
            'last_name' => 'Nom de famille',
            'phone' => 'Numéro de téléphone'
        ],
        'ru' => [
            'email' => 'Email адрес',
            'password' => 'Пароль',
            'username' => 'Имя пользователя',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Номер телефона'
        ]
    ];
}

// Usage with different languages
$validator = new Validony(
    $_POST,
    MyMessages::$messages,
    MyMessages::$fieldNames,
    MyChecker::class,
    [],
    'es'  // Use Spanish language
);
```

### Dynamic Language Switching

```php
class MultiLanguageValidator
{
    private $validator;
    
    public function validateInLanguage($data, $rules, $language = 'en')
    {
        $this->validator = new Validony(
            $data,
            MyMessages::$messages,
            MyMessages::$fieldNames,
            MyChecker::class,
            [],
            $language  // Dynamic language selection
        );
        
        $this->validator->CheckData($rules);
        
        return [
            'valid' => $this->validator->isValid(),
            'errors' => $this->validator->getErrors(true),
            'language' => $language
        ];
    }
}

// Usage
$multiValidator = new MultiLanguageValidator();

// Validate in English
$result_en = $multiValidator->validateInLanguage($_POST, $rules, 'en');

// Validate in Spanish
$result_es = $multiValidator->validateInLanguage($_POST, $rules, 'es');

// Validate in Russian
$result_ru = $multiValidator->validateInLanguage($_POST, $rules, 'ru');
```

## 🔍 Debugging and Logging

### Enable Detailed Messages

```php
$validator = new Validony(
    $_POST,
    MyMessages::$messages,  // Use your custom messages even for debugging
    MyMessages::$fieldNames,
    MyChecker::class,       // Use your custom checker
    [],
    'en',
    true,   // Show field names
    true,   // Show field values (for debugging)
    true,   // Collect all errors
    false
);
```

### Custom Error Handler

```php
class DebugErrorHandler {
    public static function logError($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] Validation Error: {$message}\n";
        file_put_contents('validation.log', $logMessage, FILE_APPEND);
    }
}

$validator = new Validony(
    $_POST,
    MyMessages::$messages,
    MyMessages::$fieldNames,
    MyChecker::class,
    [DebugErrorHandler::class, 'logError'],
    'en',
    true,
    true,
    true,
    true  // Automatically call callback
);
```

## 📚 API Reference

### Main Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `__construct()` | Create validator instance | `void` |
| `CheckData()` | Validate by rules | `bool` |
| `ValidateList()` | Validate via rule lists | `void` |
| `CheckLikeFieldsData()` | Validate similar fields | `bool` |
| `isValid()` | Check validation result | `bool` |
| `getErrors()` | Get errors | `array` |

### Static Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `Validon::CheckData()` | Static validation | `array` |
| `Validon::ValidateList()` | Static validation via lists | `array` |
| `Validon::CheckLikeFieldsData()` | Static validation of similar fields | `array` |

## 🤝 Contributing

We welcome contributions to the project! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

Need help? We're here for you:

- Create an [Issue](https://github.com/DaveBugg/Validony/issues)
- Check the [Wiki](https://github.com/DaveBugg/Validony/wiki)
- Email: doncineman2@gmail.com

## 🔗 Links

- [Documentation](https://github.com/DaveBugg/Validony/wiki)
- [Changelog](CHANGELOG.md)

---

**Validony** - Making data validation simple and powerful! 🚀
