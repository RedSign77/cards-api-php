# GitHub Workflows

This directory contains GitHub Actions workflows for CI/CD automation.

## Workflows

### 1. Security
**File:** `security.yml`

**Triggers:**
- Pull requests to `main` or `develop` branches
- Weekly schedule (Sundays at midnight)

**Jobs:**
- Checks for PHP dependency vulnerabilities using `composer audit`
- Checks for NPM package vulnerabilities

## Status Badges

Add to your main README.md:

```markdown
![Security](https://github.com/RedSign77/cards-api-php/workflows/Security/badge.svg)
```
