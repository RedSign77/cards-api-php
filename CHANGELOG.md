# Changelog

All notable changes to this project will be documented in this file.

## [1.1.0] - 2025-12-24

### Changed
- Email templates now use Laravel mail layout with Cards Forge custom theme
- Email Templates and Scheduled Emails moved to System Settings navigation group
- Preview modal now displays emails with actual site header and footer styling
- Renamed "Run Now" to "Force Run" action with enhanced feedback showing sent/skipped counts
- Force Run action now explicitly shows scheduled time being bypassed in confirmation modal

### Added
- Custom Email Template System for managing email templates with Markdown support
- EmailTemplate model and database table for storing reusable email templates
- EmailTemplateResource in Filament admin panel (supervisor-only access)
- Markdown editor with live preview functionality for email content
- Variable injection system supporting {{ variable }} placeholders in subject and body
- TemplateEmail mailable class for sending template-based emails with custom HTML layout
- Preview modal for templates showing rendered content
- Available variables cheat sheet in template form
- Custom HTML email template with responsive design and inline CSS
- Feature documentation in dps/features/custom-email-templates.md
- Advanced Scheduled Email Dispatcher for automated email campaigns
- ScheduledEmail model with cron expression scheduling and multi-source data support
- EmailDispatchLog model for deduplication tracking
- Cron expression validation with human-friendly display
- Dynamic recipient targeting (all users, specific roles, individual selection)
- Order status filtering with configurable look-back windows
- ScheduledEmailResource with reactive UI based on data source
- ProcessScheduledEmails command for email dispatch with deduplication
- Automatic scheduling via Laravel task scheduler (runs every minute)
- Force Run action with detailed execution feedback (sent/skipped counts)
- Bulk Force Run action to execute multiple campaigns simultaneously
- Clear Dispatch Logs header action for resetting deduplication during testing
- Comprehensive execution statistics tracking
- Enhanced testing capabilities with immediate execution bypassing schedules
- Feature documentation in dps/features/advanced-scheduled-email-dispatcher.md
