# Contributing to Validony

Thank you for your interest in contributing to Validony! We welcome contributions from everyone.

## 🚀 Getting Started

### Prerequisites
- PHP 8.0 or higher
- Composer
- Git

### Setting Up Development Environment

1. **Fork the repository**
   ```bash
   # Click the "Fork" button on GitHub
   ```

2. **Clone your fork**
   ```bash
   git clone https://github.com/YOUR_USERNAME/Validony.git
   cd Validony
   ```

3. **Install dependencies**
   ```bash
   composer install
   ```

4. **Create a new branch**
   ```bash
   git checkout -b feature/your-feature-name
   # or
   git checkout -b fix/your-bug-fix
   ```

## 📋 How to Contribute

### 🐛 Reporting Bugs

1. **Check existing issues** to avoid duplicates
2. **Use the bug report template** when creating a new issue
3. **Provide detailed information**:
   - PHP version
   - Validony version
   - Steps to reproduce
   - Expected vs actual behavior
   - Code examples

### ✨ Suggesting Features

1. **Check existing feature requests** to avoid duplicates
2. **Use the feature request template**
3. **Explain the use case** and why it would be beneficial
4. **Provide code examples** of how you'd like to use the feature

### 🔧 Code Contributions

#### Code Style Guidelines

- Follow **PSR-12** coding standards
- Use **meaningful variable and method names**
- Add **PHPDoc comments** for all public methods
- Keep methods **focused and small**
- Use **type hints** where possible

#### Example Code Style:
```php
/**
 * Validates data according to specified rules.
 *
 * @param array $fields Validation rules
 * @param mixed|null $callback Override callback setting
 * @return bool True if validation passes
 */
public function CheckData(array $fields, mixed $callback = null): bool
{
    // Implementation
}
```

#### Testing

- **Write tests** for new features and bug fixes
- **Ensure all tests pass** before submitting
- **Test with different PHP versions** if possible

#### Documentation

- **Update README.md** if your changes affect usage
- **Add code examples** for new features
- **Update PHPDoc comments** as needed

## 🔄 Pull Request Process

1. **Create a descriptive PR title**
   - `feat: add new validation method`
   - `fix: resolve issue with callback handling`
   - `docs: update installation instructions`

2. **Fill out the PR template** completely

3. **Ensure your code**:
   - Follows coding standards
   - Includes tests
   - Passes all existing tests
   - Is properly documented

4. **Link related issues** using `Fixes #123` or `Closes #123`

5. **Be responsive** to feedback and requested changes

## 🏷️ Commit Message Guidelines

Use conventional commit format:

```
type(scope): description

[optional body]

[optional footer]
```

### Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### Examples:
```
feat(validator): add support for custom error messages
fix(constructor): resolve issue with callback parameter
docs(readme): add examples for multi-language support
```

## 🧪 Testing Guidelines

### Running Tests
```bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/ValidonyTest.php
```

### Writing Tests
- Place tests in the `tests/` directory
- Use descriptive test method names
- Test both success and failure scenarios
- Mock external dependencies

Example test:
```php
public function testValidationPassesWithValidData(): void
{
    $validator = new Validony(['email' => 'test@example.com']);
    $rules = ['email' => [Checker::required, Checker::email]];
    
    $result = $validator->CheckData($rules);
    
    $this->assertTrue($result);
    $this->assertTrue($validator->isValid());
}
```

## 📚 Documentation Guidelines

### README Updates
- Keep examples **simple and clear**
- Use **real-world scenarios**
- Include **error handling** examples
- Test all code examples

### Code Documentation
- Document **all public methods**
- Include **parameter types** and descriptions
- Provide **usage examples** in PHPDoc
- Explain **complex logic** with inline comments

## 🎯 Areas for Contribution

We especially welcome contributions in these areas:

### High Priority
- 🧪 **Unit tests** and test coverage improvement
- 📚 **Documentation** improvements and examples
- 🐛 **Bug fixes** and stability improvements
- ⚡ **Performance** optimizations

### Medium Priority
- ✨ **New validation methods** for common use cases
- 🌐 **Internationalization** improvements
- 🔧 **Developer experience** enhancements
- 📱 **Framework integrations** (Laravel, Symfony, etc.)

### Low Priority
- 🎨 **Code style** improvements
- 🔄 **Refactoring** for better maintainability
- 📊 **Benchmarking** and performance analysis

## ❓ Questions?

- 📧 **Email**: doncineman2@gmail.com
- 💬 **GitHub Discussions**: [Start a discussion](https://github.com/DaveBugg/Validony/discussions)
- 🐛 **Issues**: [Create an issue](https://github.com/DaveBugg/Validony/issues)

## 📄 License

By contributing to Validony, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to Validony! 🚀 