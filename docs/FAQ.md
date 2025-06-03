# Frequently Asked Questions (FAQ)

## 🚀 Getting Started

### Q: How do I install Validony?
**A:** Use Composer to install Validony:
```bash
composer require davebugg/validony
```

### Q: What PHP version is required?
**A:** Validony requires PHP 8.0 or higher.

### Q: Can I use Validony with older PHP versions?
**A:** No, Validony uses modern PHP features like union types and named arguments that require PHP 8.0+.

## 🔧 Basic Usage

### Q: How do I validate a simple form?
**A:** Here's a basic example:
```php
use DavesValidator\Validator\Validony;
use DavesValidator\Validator\Checker;

$validator = new Validony($_POST);
$rules = [
    'email' => [Checker::required, Checker::email],
    'password' => [Checker::required, Checker::minLength]
];

$validator->CheckData($rules);

if ($validator->isValid()) {
    // Process form
} else {
    $errors = $validator->getErrors();
    // Handle errors
}
```

### Q: How do I get all validation errors instead of stopping on the first one?
**A:** Set `getAllErrors` to `true` in the constructor:
```php
$validator = new Validony(
    $_POST,     // data
    false,      // custom messages
    false,      // custom field names
    false,      // checker class
    [],         // callback
    'en',       // language
    true,       // print field
    false,      // print data
    true,       // GET ALL ERRORS
    false       // do callback
);
```

### Q: How do I show field names in error messages?
**A:** Field names are shown by default. To disable, set `printField` to `false`:
```php
$validator = new Validony($_POST, false, false, false, [], 'en', false);
```

## 🌐 Internationalization

### Q: How do I use custom error messages?
**A:** Pass custom messages array to the constructor:
```php
$customMessages = [
    'en' => [
        'required' => 'The :field field is required',
        'email' => 'Please enter a valid email address'
    ]
];

$validator = new Validony($_POST, $customMessages);
```

### Q: How do I change the language?
**A:** Set the language parameter in the constructor:
```php
$validator = new Validony($_POST, false, false, false, [], 'es'); // Spanish
```

### Q: How do I rename fields in error messages?
**A:** Use custom field names:
```php
$customFieldNames = [
    'en' => [
        'email' => 'Email Address',
        'pwd' => 'Password'
    ]
];

$validator = new Validony($_POST, false, $customFieldNames);
```

## 🔄 Advanced Features

### Q: How do I validate multiple fields with similar names?
**A:** Use `CheckLikeFieldsData()`:
```php
// For fields like: product_name_1, product_name_2, product_price_1, etc.
$rules = [
    'product_name' => [Checker::required, Checker::minLength],
    'product_price' => [Checker::required, Checker::numeric]
];

$validator->CheckLikeFieldsData($rules);
```

### Q: How do I use validation lists?
**A:** Create a class in the Lists folder:
```php
// Lists/UserValidator.php
class UserValidator {
    public static function registration(): array {
        return [
            'username' => [Checker::required, Checker::minLength],
            'email' => [Checker::required, Checker::email]
        ];
    }
}

// Usage
$validator->ValidateList('registration');
```

### Q: How do I set up custom callbacks?
**A:** Pass callback array to constructor:
```php
class MyHandler {
    public static function handleError($message) {
        // Custom error handling
        error_log($message);
    }
}

$validator = new Validony($_POST, false, false, false, [MyHandler::class, 'handleError']);
```

## 🐛 Troubleshooting

### Q: Why am I getting "Class not found" errors?
**A:** Make sure you:
1. Installed via Composer: `composer require davebugg/validony`
2. Include the autoloader: `require_once 'vendor/autoload.php'`
3. Use correct namespaces: `use DavesValidator\Validator\Validony;`

### Q: Why are my validation rules not working?
**A:** Check that:
1. You're using the correct Checker methods: `Checker::required`, `Checker::email`, etc.
2. Your data array has the correct field names
3. You're calling `CheckData()` before `isValid()`

### Q: How do I debug validation issues?
**A:** Enable detailed error information:
```php
$validator = new Validony(
    $_POST,
    false,      // custom messages
    false,      // custom field names
    false,      // checker class
    [],         // callback
    'en',       // language
    true,       // show field names
    true,       // SHOW FIELD VALUES (for debugging)
    true,       // get all errors
    false       // callback
);
```

### Q: Why is my callback not being called?
**A:** Make sure:
1. You set `doCallback` to `true` in constructor, OR
2. You pass `true` as callback parameter in validation methods
3. Your callback class and method exist and are accessible

## ⚡ Performance

### Q: Is Validony fast enough for production?
**A:** Yes! Validony is designed for production use. For better performance:
- Use specific validation rules instead of generic ones
- Avoid showing field values in production (`printData = false`)
- Consider caching validation rules for repeated use

### Q: How do I optimize validation for large datasets?
**A:** 
- Set `getAllErrors = false` to stop on first error
- Use batch validation for similar data structures
- Consider validating only changed fields in updates

## 🔧 Integration

### Q: Can I use Validony with Laravel?
**A:** Yes! Validony works with any PHP framework:
```php
// In Laravel controller
public function store(Request $request) {
    $validator = new Validony($request->all());
    $validator->CheckData($rules);
    
    if (!$validator->isValid()) {
        return back()->withErrors($validator->getErrors()['errors']);
    }
    
    // Process valid data
}
```

### Q: How do I integrate with Symfony?
**A:** Similar to Laravel:
```php
// In Symfony controller
public function create(Request $request) {
    $data = $request->request->all();
    $validator = new Validony($data);
    $validator->CheckData($rules);
    
    if (!$validator->isValid()) {
        // Handle errors
    }
}
```

### Q: Can I use Validony for API validation?
**A:** Absolutely! It's perfect for API validation:
```php
$data = json_decode(file_get_contents('php://input'), true);
$validator = new Validony($data);
$validator->CheckData($rules);

if (!$validator->isValid()) {
    http_response_code(400);
    echo json_encode(['errors' => $validator->getErrors()['errors']]);
    exit;
}
```

## 📚 Still Need Help?

- 📖 Check the [Documentation](Home)
- 🐛 [Report a bug](https://github.com/DaveBugg/Validony/issues/new?template=bug_report.md)
- ✨ [Request a feature](https://github.com/DaveBugg/Validony/issues/new?template=feature_request.md)
- ❓ [Ask a question](https://github.com/DaveBugg/Validony/issues/new?template=question.md)
- 📧 Email: doncineman2@gmail.com

---

Can't find your question? [Ask it here](https://github.com/DaveBugg/Validony/issues/new?template=question.md)! 