# Quick Start Guide

Get up and running with Validony in just a few minutes!

## 📦 Installation

```bash
composer require davebugg/validony
```

## 🚀 Basic Usage

### 1. Simple Validation

```php
<?php
use DavesValidator\Validator\Validony;
use DavesValidator\Validator\Checker;

// Your data to validate
$data = [
    'email' => 'user@example.com',
    'password' => 'secret123',
    'age' => '25'
];

// Create validator
$validator = new Validony($data);

// Define validation rules
$rules = [
    'email' => [Checker::required, Checker::email],
    'password' => [Checker::required, Checker::minLength],
    'age' => [Checker::numeric, Checker::min]
];

// Validate
$validator->CheckData($rules);

// Check result
if ($validator->isValid()) {
    echo "✅ All data is valid!";
} else {
    $errors = $validator->getErrors();
    echo "❌ Validation failed: " . implode(', ', $errors['errors']);
}
```

### 2. Get Detailed Error Information

```php
// Get errors with field names
$errors = $validator->getErrors(true);

if (!$validator->isValid()) {
    foreach ($errors['errors'] as $index => $error) {
        $field = $errors['fields'][$index];
        echo "Field '{$field}': {$error}\n";
    }
}
```

### 3. Collect All Errors

```php
// Configure to collect all errors instead of stopping on first
$validator = new Validony(
    $data,                  // Data to validate
    false,                  // Use default messages
    false,                  // Use default field names
    false,                  // Use default checker
    [],                     // No callback
    'en',                   // English language
    true,                   // Show field names
    false,                  // Don't show values
    true,                   // Collect ALL errors
    false                   // Don't auto-callback
);

$validator->CheckData($rules);
$errors = $validator->getErrors(true);
```

## 🎯 Common Validation Scenarios

### Form Validation

```php
// Registration form validation
$registrationRules = [
    'username' => [Checker::required, Checker::minLength, Checker::maxLength],
    'email' => [Checker::required, Checker::email],
    'password' => [Checker::required, Checker::password],
    'confirm_password' => [Checker::required],
    'terms' => [Checker::required, Checker::checkbox]
];

$validator = new Validony($_POST);
$validator->CheckData($registrationRules);

if ($validator->isValid()) {
    // Process registration
    registerUser($_POST);
} else {
    // Show errors
    $errors = $validator->getErrors();
    showFormErrors($errors['errors']);
}
```

### API Validation

```php
// API endpoint validation
$apiData = json_decode(file_get_contents('php://input'), true);

$validator = new Validony($apiData);
$rules = [
    'name' => [Checker::required, Checker::string],
    'price' => [Checker::required, Checker::numeric, Checker::min],
    'category_id' => [Checker::required, Checker::integer]
];

$validator->CheckData($rules);

if ($validator->isValid()) {
    // Process API request
    $response = ['status' => 'success', 'data' => processData($apiData)];
} else {
    // Return validation errors
    $errors = $validator->getErrors();
    $response = ['status' => 'error', 'errors' => $errors['errors']];
}

header('Content-Type: application/json');
echo json_encode($response);
```

### Multiple Similar Fields

```php
// Validate multiple product fields
$_POST = [
    'product_name_1' => 'Product A',
    'product_name_2' => 'Product B',
    'product_price_1' => '100',
    'product_price_2' => '200'
];

$validator = new Validony($_POST);

// Rules for fields starting with specific prefixes
$rules = [
    'product_name' => [Checker::required, Checker::minLength],
    'product_price' => [Checker::required, Checker::numeric, Checker::min]
];

$validator->CheckLikeFieldsData($rules);

if ($validator->isValid()) {
    echo "All products are valid!";
}
```

## 🔧 Configuration Options

### Constructor Parameters

```php
$validator = new Validony(
    $data,                  // Data to validate (required)
    $customMessages,        // Custom error messages (optional)
    $customFieldNames,      // Custom field names (optional)
    $checkerClass,          // Custom checker class (optional)
    $callback,              // Error callback [class, method] (optional)
    'en',                   // Language (default: 'en')
    true,                   // Show field names (default: true)
    false,                  // Show field values (default: false)
    false,                  // Get all errors (default: false)
    false                   // Auto callback (default: false)
);
```

### Method Overrides

```php
// Use constructor settings
$validator->CheckData($rules);

// Override specific settings for this call
$validator->CheckData(
    $rules,
    true,    // Enable callback for this call
    false,   // Don't show field names
    true,    // Show field values
    true     // Get all errors
);
```

## 📚 Next Steps

- 📖 Read the [Constructor Parameters](Constructor-Parameters) guide
- 🔧 Learn about [Validation Methods](Validation-Methods)
- 🌐 Explore [Multi-language Support](Multi-language-Support)
- 🎯 Check out [Form Validation Examples](Form-Validation-Examples)

## 💡 Tips

1. **Start simple** - Use basic validation first, then add complexity
2. **Test thoroughly** - Validate with both valid and invalid data
3. **Handle errors gracefully** - Always check `isValid()` before processing
4. **Use meaningful field names** - They appear in error messages
5. **Consider user experience** - Show all errors at once when possible

---

Ready to dive deeper? Check out the [Validation Methods](Validation-Methods) documentation! 