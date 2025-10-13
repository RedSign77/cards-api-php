# Email Configuration Guide

The Cards Forge API sends emails for user registration, email verification, and supervisor notifications. This guide explains how to configure email sending.

## Quick Setup Options

### Option 1: Gmail SMTP (Recommended for Production)

1. **Enable 2-Factor Authentication** on your Google Account
   - Go to https://myaccount.google.com/security
   - Enable 2-Step Verification

2. **Generate App Password**
   - Visit https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (Custom name)"
   - Enter "Cards Forge API" as the name
   - Copy the 16-character password

3. **Update .env File**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=signred@gmail.com
   MAIL_PASSWORD=your-16-char-app-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@cardsforge.com"
   MAIL_FROM_NAME="Cards Forge"
   ```

4. **Clear Configuration Cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Option 2: Mailjet (Alternative Service)

1. **Sign up at Mailjet**
   - Visit https://www.mailjet.com/
   - Create a free account (6,000 emails/month free)

2. **Get API Credentials**
   - Go to Account Settings → REST API → API Key Management
   - Copy your API Key and Secret Key

3. **Update .env File**
   ```env
   MAIL_MAILER=mailjet
   MAILJET_APIKEY=your-api-key
   MAILJET_APISECRET=your-secret-key
   MAIL_FROM_ADDRESS="noreply@cardsforge.com"
   MAIL_FROM_NAME="Cards Forge"
   ```

4. **Verify Sender Address**
   - Add and verify your sender email in Mailjet dashboard
   - Use the verified email as MAIL_FROM_ADDRESS

### Option 3: Log Driver (Development/Testing Only)

For local development when you don't need actual emails:

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@cardsforge.com"
MAIL_FROM_NAME="Cards Forge"
```

Emails will be saved to `storage/logs/laravel.log` instead of being sent.

## Other SMTP Services

### Mailtrap (Testing)
Perfect for development - catches all emails without sending them.

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cardsforge.com"
MAIL_FROM_NAME="Cards Forge"
```

### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@cardsforge.com"
MAIL_FROM_NAME="Cards Forge"
```

### Amazon SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS="noreply@cardsforge.com"
MAIL_FROM_NAME="Cards Forge"
```

## Email Notifications

The following emails are sent automatically:

### User Registration
- **To:** signred@gmail.com (admin notification)
- **Trigger:** When a new user registers
- **Purpose:** Notify admin of new user

### Email Verification
- **To:** New user's email address
- **Trigger:** When a user registers
- **Purpose:** Verify user's email address
- **Contains:** Verification link valid for 60 minutes

### Email Confirmed
- **To:** All supervisors
- **Trigger:** When a user verifies their email
- **Purpose:** Notify supervisors to approve the account

### New Game Added
- **To:** signred@gmail.com (admin notification)
- **Trigger:** When a new game is created
- **Purpose:** Notify admin of new game

## Troubleshooting

### Gmail "Less Secure Apps" Error
Gmail requires App Passwords when using 2FA. Never use your main password for SMTP.

### Connection Timeout
- Check firewall allows outbound connections on port 587 (or 465 for SSL)
- Try port 465 with `MAIL_ENCRYPTION=ssl` if 587 fails
- Ensure your server can reach the SMTP host

### Authentication Failed
- Double-check username and password
- For Gmail: Use App Password, not account password
- Check if the service requires specific username format

### Emails Not Arriving
- Check spam/junk folders
- Verify sender domain/email is authorized
- Check email service dashboard for bounces
- Use `php artisan queue:work` if using queued emails

### Testing Email Configuration
```bash
# Send a test email
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('your-email@example.com')->subject('Test'); });
>>> exit
```

## Production Recommendations

1. **Use a Dedicated Email Service** - Gmail has sending limits
2. **Set up SPF/DKIM Records** - Improves deliverability
3. **Use Queue System** - Don't block requests waiting for email
4. **Monitor Bounces** - Set up bounce handling
5. **Use Environment Variables** - Never commit credentials

## Changing Admin Notification Email

To change where admin notifications are sent, update:
- `app/Models/User.php` line 74
- `app/Models/Game.php` line 54

Currently set to: `signred@gmail.com`

## Support

For email configuration issues, contact: signred@gmail.com
