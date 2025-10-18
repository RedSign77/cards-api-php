# GitHub Workflows

This directory contains GitHub Actions workflows for CI/CD automation.

## Workflows

### 1. CI (Continuous Integration)
**File:** `ci.yml`

**Triggers:**
- Push to `main`, `develop`, or `feature/*` branches
- Pull requests to `main` or `develop` branches

**Jobs:**
- **Tests**: Runs PHPUnit tests on PHP 8.2, 8.3, and 8.4
- **Code Quality**: Checks code style using Laravel Pint
- **Build Assets**: Builds frontend assets with Vite and uploads artifacts

### 2. Deploy
**File:** `deploy.yml`

**Triggers:**
- Push to `main` branch
- Manual workflow dispatch

**Jobs:**
- Deploys to production server via SSH
- Runs migrations, optimizes caches
- Restarts queue workers

**Required Secrets:**
- `DEPLOY_HOST` - Server hostname/IP
- `DEPLOY_USER` - SSH username
- `DEPLOY_KEY` - SSH private key
- `DEPLOY_PORT` - SSH port (default: 22)
- `DEPLOY_PATH` - Deployment path on server

### 3. Security
**File:** `security.yml`

**Triggers:**
- Push to `main` or `develop` branches
- Pull requests to `main` or `develop` branches
- Weekly schedule (Sundays at midnight)

**Jobs:**
- Checks for PHP dependency vulnerabilities using `composer audit`
- Checks for NPM package vulnerabilities

## Setup Instructions

### For Deployment Workflow

1. Generate SSH key pair:
   ```bash
   ssh-keygen -t ed25519 -C "github-actions@cards-forge" -f deploy_key
   ```

2. Add public key (`deploy_key.pub`) to server's `~/.ssh/authorized_keys`

3. Add secrets to GitHub repository:
   - Go to Settings → Secrets and variables → Actions
   - Add the following secrets:
     - `DEPLOY_HOST`: Your server IP or domain
     - `DEPLOY_USER`: SSH username (e.g., `www-data`)
     - `DEPLOY_KEY`: Contents of `deploy_key` (private key)
     - `DEPLOY_PATH`: Full path to application (e.g., `/var/www/cards-api-php`)
     - `DEPLOY_PORT`: SSH port (optional, defaults to 22)

4. Ensure server has:
   - PHP 8.2+
   - Composer
   - Node.js 20+
   - Git access to repository

### Disabling Workflows

To disable a workflow, either:
1. Delete the workflow file
2. Add to the workflow file:
   ```yaml
   on:
     workflow_dispatch:  # Manual trigger only
   ```

## Status Badges

Add to your main README.md:

```markdown
![CI](https://github.com/YOUR_USERNAME/cards-api-php/workflows/CI/badge.svg)
![Deploy](https://github.com/YOUR_USERNAME/cards-api-php/workflows/Deploy/badge.svg)
![Security](https://github.com/YOUR_USERNAME/cards-api-php/workflows/Security/badge.svg)
```
