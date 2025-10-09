# reCAPTCHA Setup Guide

Google reCAPTCHA has been integrated into the Cards Forge API to protect forms from spam and abuse.

## Configuration

### 1. Get reCAPTCHA Keys

1. Visit [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Register a new site with the following settings:
   - **Label:** Cards Forge
   - **reCAPTCHA type:** reCAPTCHA v2 (Checkbox)
   - **Domains:** Add your domain(s)
3. Copy the **Site Key** and **Secret Key**

### 2. Configure Environment Variables

Add the following to your `.env` file:

```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
```

### 3. Install Dependencies

The `google/recaptcha` package has been added to `composer.json`. Run:

```bash
composer install
```

## Protected Forms

reCAPTCHA has been added to the following forms:

### Filament Admin Panel
- **Login Page** (`/admin/login`)
- **Registration Page** (`/admin/register`)

### API Endpoints
- `POST /api/user/register`
- `POST /api/user/login`
- `POST /api/supervisor/login`

## Testing

### Disable reCAPTCHA for Testing

To disable reCAPTCHA validation during testing or development, simply leave the environment variables empty:

```env
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

The validation rule will automatically skip verification when keys are not configured.

### Testing with reCAPTCHA

For API testing with reCAPTCHA enabled, include the `g-recaptcha-response` field in your requests:

```bash
curl -X POST https://your-domain.com/api/user/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "your_password",
    "g-recaptcha-response": "03AGdBq24..."
  }'
```

## Implementation Details

### Files Created/Modified

**Configuration:**
- `config/recaptcha.php` - reCAPTCHA configuration
- `.env.example` - Added reCAPTCHA key placeholders

**Validation:**
- `app/Rules/Recaptcha.php` - Custom validation rule

**Filament Pages:**
- `app/Filament/Pages/Auth/Login.php` - Custom login page
- `app/Filament/Pages/Auth/Register.php` - Custom registration page
- `resources/views/filament/pages/auth/login.blade.php` - Login view
- `resources/views/filament/pages/auth/register.blade.php` - Registration view

**API Controllers:**
- `app/Http/Controllers/Api/ApiController.php` - Added reCAPTCHA validation to login/register
- `app/Http/Controllers/Api/SupervisorController.php` - Added reCAPTCHA validation to login

**Providers:**
- `app/Providers/Filament/AdminPanelProvider.php` - Configured custom auth pages

### How It Works

1. **Frontend:** The reCAPTCHA widget is rendered on forms using Google's JavaScript library
2. **User Interaction:** Users must complete the reCAPTCHA challenge
3. **Token Generation:** Google generates a token upon successful completion
4. **Backend Validation:** The token is sent with the form and validated server-side using Google's API
5. **Response:** The form is processed only if the token is valid

### Security Features

- Server-side verification prevents token manipulation
- IP address checking for additional security
- Automatic failure on missing or invalid tokens
- Graceful degradation when reCAPTCHA is not configured

## Troubleshooting

### reCAPTCHA not showing

- Check that `RECAPTCHA_SITE_KEY` is set in `.env`
- Clear cache: `php artisan config:clear`
- Verify domain is whitelisted in Google reCAPTCHA admin

### Validation always fails

- Check that `RECAPTCHA_SECRET_KEY` is correct
- Ensure your server can reach Google's API
- Check firewall settings

### API requests failing

- Include `g-recaptcha-response` field in requests
- Obtain token from reCAPTCHA widget on frontend
- Token is single-use and expires after 2 minutes

## Support

For issues related to reCAPTCHA integration, contact: info@webtech-solutions.hu
