<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>{{ setting('legal_privacy_title', 'Privacy Policy - Cards Forge') }}</title>
    <meta name="description" content="{{ setting('legal_privacy_description', 'GDPR-compliant Privacy Policy for Cards Forge. Learn how we collect, use, and protect your personal data.') }}">
    <meta name="keywords" content="privacy policy, cards forge, GDPR, data protection, personal data, EU law, Hungarian law, privacy rights">
    <meta name="author" content="{{ setting('seo_author', 'Webtech Solutions') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ setting('legal_privacy_title', 'Privacy Policy - Cards Forge') }}">
    <meta property="og:description" content="{{ setting('legal_privacy_description', 'GDPR-compliant Privacy Policy for Cards Forge.') }}">
    <meta property="og:image" content="{{ asset(setting('og_image', '/images/og-image.png')) }}">
    <meta property="og:site_name" content="{{ setting('site_name', 'Cards Forge') }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ setting('legal_privacy_title', 'Privacy Policy - Cards Forge') }}">
    <meta name="twitter:description" content="{{ setting('legal_privacy_description', 'GDPR-compliant Privacy Policy for Cards Forge.') }}">
    <meta name="twitter:image" content="{{ asset(setting('twitter_image', '/images/twitter-image.png')) }}">
    @if(setting('twitter_handle'))
    <meta name="twitter:site" content="{{ '@' . setting('twitter_handle') }}">
    @endif

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(setting('favicon_32', '/favicon-32x32.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(setting('favicon_16', '/favicon-16x16.png')) }}">
    <link rel="apple-touch-icon" href="{{ asset(setting('apple_touch_icon', '/apple-touch-icon.png')) }}">
    <meta name="theme-color" content="{{ setting('theme_color', '#1e293b') }}">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebPage",
        "name": "{{ setting('legal_privacy_title', 'Privacy Policy') }}",
        "description": "{{ setting('legal_privacy_description', 'GDPR-compliant Privacy Policy for Cards Forge.') }}",
        "url": "{{ url()->current() }}",
        "inLanguage": "{{ app()->getLocale() }}",
        "isPartOf": {
            "@@type": "WebSite",
            "name": "{{ setting('site_name', 'Cards Forge') }}",
            "url": "{{ config('app.url') }}"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "{{ setting('company_name', 'Webtech Solutions') }}",
            "url": "{{ setting('company_website', config('app.url')) }}",
            "email": "{{ setting('company_email', 'info@webtech-solutions.hu') }}"
        }
    }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <a href="{{ route('home') }}" class="hover:text-primary-600">Cards Forge</a>
                </h1>
                <a href="{{ route('home') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    ← Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-8">
            <div class="prose dark:prose-invert max-w-none">
                <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Cards Forge – Privacy Policy</h1>

                <div class="text-sm text-gray-600 dark:text-gray-400 mb-8 space-y-1">
                    <p><strong>Last Updated:</strong> {{ date('F d, Y', filemtime(resource_path('views/legal/privacy-policy.blade.php'))) }}</p>
                    <p><strong>Operator:</strong> {{ setting('company_name', 'Webtech Solutions') }} (hereinafter referred to as "the Service Provider" or "we")</p>
                    <p><strong>Email:</strong> {{ setting('company_email', 'info@webtech-solutions.hu') }}</p>
                    <p><strong>Website:</strong> <a href="{{ setting('company_website', 'https://cardsforge.eu') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ setting('company_website', 'https://cardsforge.eu') }}</a></p>
                    <p><strong>Governing Law:</strong> {{ setting('company_country', 'Hungarian') }} and European Union law (GDPR compliant)</p>
                </div>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">1. Introduction</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Webtech Solutions ("we", "our", or "us") operates Cards Forge, a marketplace platform for physical collectible cards. We are committed to protecting your privacy and personal data in accordance with the General Data Protection Regulation (GDPR) and Hungarian data protection laws.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        This Privacy Policy explains how we collect, use, disclose, and safeguard your personal information when you use our Platform. By accessing or using Cards Forge, you acknowledge that you have read and understood this Privacy Policy and consent to the processing of your personal data as described herein.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        If you do not agree with this Privacy Policy, please do not use the Platform.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">2. Information We Collect</h2>

                    <h3 class="text-xl font-semibold mb-3 mt-6 text-gray-900 dark:text-white">2.1 Personal Information</h3>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        When you register for an account, we collect:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Name:</strong> Your full name for account identification</li>
                        <li><strong>Email Address:</strong> For account verification, notifications, and communication</li>
                        <li><strong>Password:</strong> Encrypted and securely stored</li>
                        <li><strong>Avatar Image:</strong> Optional profile picture</li>
                    </ul>

                    <h3 class="text-xl font-semibold mb-3 mt-6 text-gray-900 dark:text-white">2.2 Card Listing Information</h3>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        When you create card listings, we collect:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>Card details (title, set, language, edition, condition)</li>
                        <li>Pricing information</li>
                        <li>Card images you upload</li>
                        <li>Description and additional notes</li>
                    </ul>

                    <h3 class="text-xl font-semibold mb-3 mt-6 text-gray-900 dark:text-white">2.3 Automatically Collected Information</h3>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Login Activity:</strong> Date and time of logins/logouts</li>
                        <li><strong>Session Data:</strong> Your active sessions and device information</li>
                        <li><strong>IP Address:</strong> For security and fraud prevention</li>
                        <li><strong>Browser Information:</strong> User agent and browser type</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">3. Legal Basis and Purpose of Data Processing</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Under GDPR Article 6, we process your personal data based on the following legal grounds:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Contractual Necessity (Art. 6(1)(b)):</strong> To create and manage your account, process marketplace transactions, and fulfill our obligations under the Terms and Conditions</li>
                        <li><strong>Legitimate Interest (Art. 6(1)(f)):</strong> To detect and prevent fraud, ensure platform security, improve our services, and send service-related notifications</li>
                        <li><strong>Legal Obligation (Art. 6(1)(c)):</strong> To comply with applicable EU and Hungarian laws, including data retention requirements and responding to legal requests</li>
                        <li><strong>Consent (Art. 6(1)(a)):</strong> For optional features such as marketing communications (where applicable)</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We use your personal data for the following purposes:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Account Management:</strong> Creating and managing your user account</li>
                        <li><strong>Email Verification:</strong> Confirming your email address as required for account activation</li>
                        <li><strong>Account Approval:</strong> Supervisor review of new registrations to prevent fraud and abuse</li>
                        <li><strong>Marketplace Operations:</strong> Processing and displaying card listings, facilitating user-to-user transactions</li>
                        <li><strong>Notifications:</strong> Sending essential updates about your listings, account status, and Platform changes</li>
                        <li><strong>Security and Fraud Prevention:</strong> Detecting and preventing unauthorized access, fraudulent activities, and abuse</li>
                        <li><strong>Analytics:</strong> Understanding platform usage patterns to improve our services (anonymized where possible)</li>
                        <li><strong>Compliance:</strong> Meeting legal and regulatory requirements under Hungarian and EU law</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">4. Data Storage, Security, and Retention</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We implement appropriate technical and organizational security measures in accordance with GDPR Article 32 to protect your personal data against unauthorized or unlawful processing, accidental loss, destruction, or damage:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Encryption:</strong> Passwords are encrypted using industry-standard bcrypt hashing algorithms</li>
                        <li><strong>Secure Storage:</strong> Personal data is stored on secure servers within the European Union</li>
                        <li><strong>Access Control:</strong> Strict access limitations ensuring only authorized personnel can access personal information</li>
                        <li><strong>Regular Backups:</strong> Automated backup systems to prevent data loss</li>
                        <li><strong>SSL/TLS Encryption:</strong> All communication between your browser and our servers is encrypted using TLS 1.2 or higher</li>
                        <li><strong>Regular Security Audits:</strong> Periodic reviews of our security practices and infrastructure</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        However, no method of transmission over the Internet or electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee absolute security.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <strong>Data Retention:</strong> We retain your personal information only for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. Specifically:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>User account data: Retained while your account is active and for 90 days after account deletion (for legal and fraud prevention purposes)</li>
                        <li>Activity logs: Automatically deleted after 20 days</li>
                        <li>Transaction records: Retained for 5 years in accordance with Hungarian accounting and tax laws</li>
                        <li>Card listings: Retained until you delete them or your account is terminated</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">5. Your Rights Under GDPR</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        As a data subject under the GDPR, you have the following rights regarding your personal data:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Right of Access (Art. 15):</strong> You have the right to request a copy of the personal data we hold about you and information about how we process it</li>
                        <li><strong>Right to Rectification (Art. 16):</strong> You can update or correct inaccurate or incomplete personal information directly through your profile settings or by contacting us</li>
                        <li><strong>Right to Erasure / "Right to be Forgotten" (Art. 17):</strong> You may request deletion of your personal data, subject to legal retention requirements (e.g., accounting obligations)</li>
                        <li><strong>Right to Restriction of Processing (Art. 18):</strong> You can request that we limit the processing of your personal data under certain circumstances</li>
                        <li><strong>Right to Data Portability (Art. 20):</strong> You have the right to receive your personal data in a structured, commonly used, and machine-readable format (e.g., JSON, CSV) and transmit it to another controller</li>
                        <li><strong>Right to Object (Art. 21):</strong> You may object to processing based on legitimate interests or for direct marketing purposes</li>
                        <li><strong>Right to Withdraw Consent (Art. 7(3)):</strong> Where processing is based on consent, you have the right to withdraw your consent at any time</li>
                        <li><strong>Right to Lodge a Complaint (Art. 77):</strong> You have the right to lodge a complaint with the Hungarian National Authority for Data Protection and Freedom of Information (NAIH) or your local supervisory authority</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        To exercise any of these rights, please contact us at <a href="mailto:info@webtech-solutions.hu" class="text-blue-600 dark:text-blue-400 hover:underline">info@webtech-solutions.hu</a>. We will respond to your request within 30 days as required by GDPR Article 12(3).
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">6. Data Sharing and Third Parties</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following limited circumstances:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Supervisors:</strong> Platform supervisors can view user activity and card listings for moderation and approval purposes</li>
                        <li><strong>Public Listings:</strong> Card listings you create are visible to other registered users on the marketplace</li>
                        <li><strong>Legal Requirements:</strong> When required by Hungarian or EU law, court order, or to protect our rights and property</li>
                        <li><strong>Service Providers:</strong> We may use third-party service providers (e.g., email delivery services) who process data on our behalf under strict data processing agreements compliant with GDPR Article 28</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        All third-party service providers are located within the European Union or have adequate data protection safeguards in place. We do not transfer personal data outside the EU/EEA without appropriate safeguards.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">7. Cookies and Tracking Technologies</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We use cookies and similar tracking technologies to maintain your login session and improve your user experience. By using our Platform, you consent to our use of cookies as described below:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Essential Cookies:</strong> Required for authentication and session management (cannot be disabled)</li>
                        <li><strong>Functional Cookies:</strong> Remember your preferences and settings</li>
                        <li><strong>Analytics Cookies:</strong> Help us understand platform usage to improve our services</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        You can configure your browser to refuse cookies, but this may limit some functionality of the Platform. For more information about cookies and how to manage them, visit <a href="https://www.allaboutcookies.org" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">www.allaboutcookies.org</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">8. Children's Privacy</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Our Platform is not intended for users under the age of 16 in accordance with GDPR Article 8. We do not knowingly collect personal information from children. If we become aware that we have collected personal data from a child under 16 without parental consent, we will take immediate steps to delete that information.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        If you believe we have inadvertently collected information from a child, please contact us immediately at <a href="mailto:info@webtech-solutions.hu" class="text-blue-600 dark:text-blue-400 hover:underline">info@webtech-solutions.hu</a>.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">9. Changes to This Privacy Policy</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We may update this Privacy Policy from time to time to reflect changes in our practices, legal requirements, or Platform features. When we make material changes, we will notify you via email or by posting a prominent notice on the Platform at least 30 days before the changes take effect.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        The "Last Updated" date at the top of this policy indicates when it was last revised. Your continued use of the Platform after changes become effective constitutes acceptance of the updated Privacy Policy.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">10. Contact Information and Data Protection Officer</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        If you have any questions, concerns, or requests regarding this Privacy Policy or our data processing practices, please contact us:
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <strong>{{ setting('company_name', 'Webtech Solutions') }}</strong><br>
                        <strong>Email:</strong> <a href="mailto:{{ setting('company_email', 'info@webtech-solutions.hu') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ setting('company_email', 'info@webtech-solutions.hu') }}</a><br>
                        <strong>Website:</strong> <a href="{{ setting('company_website', 'https://cardsforge.eu') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ setting('company_website', 'https://cardsforge.eu') }}</a>
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <strong>Supervisory Authority:</strong><br>
                        If you are not satisfied with our response or believe we are processing your data unlawfully, you have the right to lodge a complaint with:
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <strong>Hungarian National Authority for Data Protection and Freedom of Information (NAIH)</strong><br>
                        Address: 1055 Budapest, Falk Miksa utca 9-11, Hungary<br>
                        Website: <a href="https://naih.hu" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">https://naih.hu</a><br>
                        Email: ugyfelszolgalat@naih.hu
                    </p>
                </section>

                <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        © {{ date('Y') }} {{ setting('site_name', 'Cards Forge') }}. All rights reserved. {{ setting('company_name', 'Webtech Solutions') }}.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-sm text-gray-600 dark:text-gray-400 space-x-4">
                <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white">Home</a>
                <span>•</span>
                <a href="{{ route('terms') }}" class="hover:text-gray-900 dark:hover:text-white">Terms & Conditions</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-gray-900 dark:hover:text-white">Privacy Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>
