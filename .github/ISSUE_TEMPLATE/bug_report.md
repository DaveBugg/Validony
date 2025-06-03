---
name: Bug Report
about: Create a report to help us improve Validony
title: "[BUG] "
labels: bug
assignees: ''

---

## 🐛 Bug Description
A clear and concise description of what the bug is.

## 🔄 Steps to Reproduce
Steps to reproduce the behavior:
1. Create validator with settings: `...`
2. Call method: `...`
3. With data: `...`
4. See error: `...`

## ✅ Expected Behavior
A clear and concise description of what you expected to happen.

## ❌ Actual Behavior
A clear and concise description of what actually happened.

## 💻 Code Example
```php
// Please provide a minimal code example that reproduces the issue
$validator = new Validony($_POST, ...);
$rules = [...];
$validator->CheckData($rules);
// Error occurs here
```

## 🌍 Environment
- **PHP Version:** [e.g. 8.1.0]
- **Validony Version:** [e.g. 2.0.0]
- **Operating System:** [e.g. Windows 11, Ubuntu 20.04]
- **Web Server:** [e.g. Apache 2.4, Nginx 1.18]

## 📋 Additional Context
Add any other context about the problem here, such as:
- Error messages
- Stack traces
- Screenshots (if applicable)
- Related issues

## 🔍 Possible Solution
If you have an idea of what might be causing the issue or how to fix it, please describe it here.
