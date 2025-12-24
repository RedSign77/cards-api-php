Feature: Advanced Scheduled Email Dispatcher

Pre-requirement: dps/features/custom-email-templates.md

User Story 1: Cron-Based Schedule Configuration
As an Administrator (supervisor role), I want to define a specific frequency for email dispatches using Cron expressions, So that I have total control over when the server processes these tasks without manual intervention.

Acceptance Criteria:
- Input field for standard 5-part Cron expressions.
- Real-time validation and "Human-Friendly" translation of the cron string.
- Toggle to enable/disable the schedule globally.

User Story 2: Targeted Recipient Logic (Users & Roles)
As an Administrator (supervisor role), I want to broadcast emails to specific segments of my user base, So that I can send role-specific updates (e.g., "Pro Members") or targeted manual announcements.

Acceptance Criteria:
- Recipient Types: "All Users", "Specific Roles", or "Individual Selection".
- Integration with existing Spatie Permissions or Laravel Role logic.
- Searchable multi-select for specific users.

User Story 3: Order Status Triggering with Historical Safety
As an Administrator (supervisor role), I want to automate emails based on Order statuses while defining a specific time-window for the check, So that customers are notified about recent changes without receiving alerts for orders from months ago.

Acceptance Criteria:
- Ability to select one or more Order Statuses as a filter.
- Look-back Parameter: Define a timeframe (e.g., "Orders updated in the last 24 hours").
- Deduplication: The system must track dispatched emails to ensure a specific Template + Order ID combination is never sent twice.

User Story 4: Dynamic Template Mapping & "Plugin-Ready" UI
As an Administrator (supervisor role), I want the UI to handle different data sources (Users vs. Orders) through a modular interface, So that I can easily add new triggers (like "Subscriptions" or "Inactive Users") in the future.

Acceptance Criteria:
- Dropdown selection of "Data Source" which dynamically changes the available filter options.
- Mapping of the "Source" data to the placeholders in the Email Template (e.g., mapping Order Number to {{ order_id }}).