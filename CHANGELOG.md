# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2024-12-19

### 🚀 Major Changes
- **BREAKING CHANGE**: Moved `printField`, `printData`, `getAllErrors` parameters from method parameters to constructor
- **BREAKING CHANGE**: Made callbacks optional but overridable in validation methods
- **BREAKING CHANGE**: Simplified `getErrors()` method to always return arrays instead of strings
- **BREAKING CHANGE**: Removed `$returnString` parameter from all static methods in `Validon` class

### ✨ Added
- New constructor parameters: `printField`, `printData`, `getAllErrors`, `doCallback`
- Method-level parameter overrides (pass `null` to use constructor settings)
- Comprehensive English documentation and README
- GitHub issue templates (bug report, feature request, question)
- Pull request template with detailed checklist
- Contributing guidelines with development setup instructions
- GitHub Wiki documentation (Home, Quick Start, FAQ)
- Professional project structure for open source

### 🔧 Changed
- Constructor signature updated with new optional parameters
- All validation methods now accept override parameters as `null` values
- `getErrors()` method simplified - removed `$string` parameter, always returns arrays
- Static methods in `Validon` class updated to match new constructor signature
- Enhanced error handling and callback system

### 📚 Documentation
- Complete README.md rewrite with examples and API reference
- Added multi-language support examples
- Created comprehensive FAQ section
- Added debugging and logging guidance
- Professional badges and project description

### 🛠️ Development
- Updated composer.json with enhanced metadata
- Added proper GitHub repository configuration
- Created issue and PR templates for better community engagement
- Established contributing guidelines and code standards

### 📦 Package
- Prepared for Packagist publication
- Enhanced package description and keywords
- Added support links and homepage

## [1.0.4] - Previous Version

### Features
- Basic data validation functionality
- Support for custom validation rules
- Multi-language error messages
- Callback system for error handling
- Static validation methods
- Similar fields validation (`CheckLikeFieldsData`)
- Validation lists support (`ValidateList`)

---

## Migration Guide from 1.x to 2.0

### Constructor Changes

**Before (1.x):**
```php
$validator = new Validony($_POST, $messages, $fieldNames, $checker, $callback, 'en');
```

**After (2.0):**
```php
$validator = new Validony(
    $_POST,           // data
    $messages,        // custom messages
    $fieldNames,      // custom field names  
    $checker,         // checker class
    $callback,        // callback array
    'en',             // language
    true,             // printField (NEW)
    false,            // printData (NEW)
    false,            // getAllErrors (NEW)
    false             // doCallback (NEW)
);
```

### Method Parameter Changes

**Before (1.x):**
```php
$validator->CheckData($rules, $callback, $printField, $printData, $getAllErrors);
$errors = $validator->getErrors($returnString, $getFields);
```

**After (2.0):**
```php
// Use constructor settings
$validator->CheckData($rules);

// Or override specific settings
$validator->CheckData($rules, $callback, $printField, $printData, $getAllErrors);

// getErrors() simplified
$errors = $validator->getErrors($getFields); // Always returns array
```

### Static Method Changes

**Before (1.x):**
```php
[$valid, $errors] = Validon::CheckData($_POST, $rules, $messages, $fieldNames, 
    $checker, $callback, $doCallback, 'en', $printField, $printData, $getAllErrors, $returnString, $getFields);
```

**After (2.0):**
```php
[$valid, $errors] = Validon::CheckData($_POST, $rules, $messages, $fieldNames,
    $checker, $callback, $doCallback, 'en', $printField, $printData, $getAllErrors, $getFields);
// $returnString parameter removed - always returns arrays
```

For detailed migration instructions and examples, see the [README.md](README.md) file. 