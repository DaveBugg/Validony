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

**Example:**

```php
// Create class with rules in Lists folder
// Lists/UserValidator.php
class UserValidator {
    public static function registrationRules(): array {
        return [
            'username' => [Checker::required, Checker::minLength],
            'email' => [Checker::required, Checker::email],
            'password' => [Checker::required, Checker::password],
            'confirm_password' => [Checker::required]
        ];
    }
}

// Usage
$validator = new Validony($_POST);
$validator->ValidateList('registrationRules');

if ($validator->isValid()) {
    echo "Registration successful!";
}
```

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

## 🔧 Advanced Examples

### Example 1: Full Setup with Callback

```php
use DavesValidator\Validator\Validony;
use DavesValidator\Validator\Checker;

// Custom callback
class MyErrorHandler {
    public static function handleError($message) {
        // Log error
        error_log("Validation error: " . $message);
        
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit;
    }
}

// Create validator with full settings
$validator = new Validony(
    $_POST,                                    // Data
    false,                                     // Standard messages
    false,                                     // Standard field names
    Checker::class,                            // Checker class
    [MyErrorHandler::class, 'handleError'],    // Callback
    'en',                                      // English language
    true,                                      // Show field name
    false,                                     // Don't show value
    true,                                      // Collect all errors
    true                                       // Call callback on error
);

$rules = [
    'email' => [Checker::required, Checker::email],
    'password' => [Checker::required, Checker::minLength],
    'age' => [Checker::numeric, Checker::min]
];

$validator->CheckData($rules);

if ($validator->isValid()) {
    echo "All data is valid!";
} else {
    $errors = $validator->getErrors(true);
    foreach ($errors['errors'] as $index => $error) {
        echo "Field {$errors['fields'][$index]}: {$error}\n";
    }
}
```

### Example 2: Registration Form Validation

```php
// Lists/RegistrationValidator.php
namespace DavesValidator\Lists;

use DavesValidator\Validator\Checker;

class RegistrationValidator {
    public static function userRegistration(): array {
        return [
            'username' => [Checker::required, Checker::minLength, Checker::maxLength],
            'email' => [Checker::required, Checker::email],
            'password' => [Checker::required, Checker::password],
            'confirm_password' => [Checker::required],
            'age' => [Checker::required, Checker::numeric, Checker::min],
            'terms' => [Checker::required, Checker::checkbox]
        ];
    }
    
    public static function userLogin(): array {
        return [
            'email' => [Checker::required, Checker::email],
            'password' => [Checker::required]
        ];
    }
}

// Usage
$validator = new Validony(
    $_POST,
    false,
    false,
    Checker::class,
    [],
    'en',
    true,    // Show field names
    false,   // Don't show values
    true,    // Collect all errors
    false    // Don't call callback automatically
);

$validator->ValidateList('userRegistration');

if ($validator->isValid()) {
    // Register user
    registerUser($_POST);
} else {
    $errors = $validator->getErrors(true);
    displayErrors($errors);
}
```

### Example 3: Multiple Fields Validation

```php
// Form data with multiple fields
$_POST = [
    'product_name_1' => 'Product 1',
    'product_name_2' => 'Product 2',
    'product_price_1' => '100',
    'product_price_2' => '200',
    'product_description_1' => 'Product 1 description',
    'product_description_2' => 'Product 2 description'
];

$validator = new Validony($_POST, false, false, false, [], 'en', true, false, true);

// Rules for all fields starting with specific prefixes
$rules = [
    'product_name' => [Checker::required, Checker::minLength],
    'product_price' => [Checker::required, Checker::numeric, Checker::min],
    'product_description' => [Checker::required]
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

### Custom Messages

```php
$customMessages = [
    'en' => [
        'required' => 'Field :field is required',
        'email' => 'Field :field must be a valid email',
        'numeric' => 'Field :field must be numeric'
    ],
    'es' => [
        'required' => 'El campo :field es obligatorio',
        'email' => 'El campo :field debe ser un email válido',
        'numeric' => 'El campo :field debe ser numérico'
    ],
    'fr' => [
        'required' => 'Le champ :field est requis',
        'email' => 'Le champ :field doit être un email valide',
        'numeric' => 'Le champ :field doit être numérique'
    ]
];

$customFieldNames = [
    'en' => [
        'email' => 'Email Address',
        'password' => 'Password'
    ],
    'es' => [
        'email' => 'Dirección de Email',
        'password' => 'Contraseña'
    ],
    'fr' => [
        'email' => 'Adresse Email',
        'password' => 'Mot de passe'
    ]
];

$validator = new Validony(
    $_POST,
    $customMessages,
    $customFieldNames,
    false,
    [],
    'es'  // Use Spanish language
);
```

## 🔍 Debugging and Logging

### Enable Detailed Messages

```php
$validator = new Validony(
    $_POST,
    false,
    false,
    false,
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
    false,
    false,
    false,
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

## 🔧 Configuration Examples

### Minimal Setup

```php
$validator = new Validony($_POST);
$validator->CheckData($rules);
```

### Production Setup

```php
$validator = new Validony(
    $_POST,                    // Data
    $customMessages,           // Custom messages
    $customFieldNames,         // Custom field names
    MyChecker::class,          // Custom checker
    [Logger::class, 'log'],    // Error logging
    'en',                      // Language
    true,                      // Show field names
    false,                     // Hide values (security)
    true,                      // Collect all errors
    false                      // Manual error handling
);
```

### Development Setup

```php
$validator = new Validony(
    $_POST,                    // Data
    false,                     // Default messages
    false,                     // Default field names
    Checker::class,            // Default checker
    [Debug::class, 'dump'],    // Debug callback
    'en',                      // Language
    true,                      // Show field names
    true,                      // Show values (debugging)
    true,                      // Collect all errors
    true                       // Auto callback
);
```

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
