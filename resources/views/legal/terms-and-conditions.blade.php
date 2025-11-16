<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>{{ setting('legal_terms_title', 'Terms and Conditions - Cards Forge') }}</title>
    <meta name="description" content="{{ setting('legal_terms_description', 'Terms and Conditions for Cards Forge marketplace platform. Hungarian and EU law compliant.') }}">
    <meta name="keywords" content="terms and conditions, cards forge, marketplace terms, user agreement, GDPR, EU law, Hungarian law">
    <meta name="author" content="{{ setting('seo_author', 'Webtech Solutions') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ setting('legal_terms_title', 'Terms and Conditions - Cards Forge') }}">
    <meta property="og:description" content="{{ setting('legal_terms_description', 'Terms and Conditions for Cards Forge marketplace platform.') }}">
    <meta property="og:image" content="{{ asset(setting('og_image', '/images/og-image.png')) }}">
    <meta property="og:site_name" content="{{ setting('site_name', 'Cards Forge') }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ setting('legal_terms_title', 'Terms and Conditions - Cards Forge') }}">
    <meta name="twitter:description" content="{{ setting('legal_terms_description', 'Terms and Conditions for Cards Forge marketplace platform.') }}">
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
        "name": "{{ setting('legal_terms_title', 'Terms and Conditions') }}",
        "description": "{{ setting('legal_terms_description', 'Terms and Conditions for Cards Forge marketplace platform.') }}",
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
                <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Cards Forge – Terms and Conditions</h1>

                <div class="text-sm text-gray-600 dark:text-gray-400 mb-8 space-y-1">
                    <p><strong>Last Updated:</strong> {{ date('F d, Y', filemtime(resource_path('views/legal/terms-and-conditions.blade.php'))) }}</p>
                    <p><strong>Operator:</strong> {{ setting('company_name', 'Webtech Solutions') }} (hereinafter referred to as "the Service Provider" or "we")</p>
                    <p><strong>Email:</strong> {{ setting('company_email', 'info@webtech-solutions.hu') }}</p>
                    <p><strong>Website:</strong> <a href="{{ setting('company_website', 'https://cardsforge.eu') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ setting('company_website', 'https://cardsforge.eu') }}</a></p>
                    <p><strong>Governing Law:</strong> {{ setting('company_country', 'Hungarian') }} and European Union law</p>
                </div>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">1. Acceptance of Terms</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        By accessing or using the Cards Forge platform ("the Platform"), you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions ("Terms").
                        If you do not agree with any part of these Terms, you must not use the Platform.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        These Terms are supplemented by:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>our Privacy Policy (GDPR compliant),</li>
                        <li>any Community Guidelines or Marketplace Rules published on the Platform.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">2. User Accounts</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        To access certain features of the Platform, you must create an account.
                        By registering, you agree to:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>provide accurate, complete, and up-to-date information,</li>
                        <li>maintain the confidentiality of your login credentials,</li>
                        <li>notify us immediately if you suspect unauthorized access to your account,</li>
                        <li>accept full responsibility for all activities under your account.</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Account activation may require email verification and supervisor approval before full access is granted.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">3. Marketplace Services</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Cards Forge operates as a facilitator for buying, selling, and trading physical collectible cards between users ("Sellers" and "Buyers").
                        The Platform does not own or sell the listed cards and acts only as an intermediary for user-to-user transactions.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        By listing or purchasing cards, you agree that:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>All listings must be accurate, truthful, and comply with applicable laws.</li>
                        <li>Card conditions and descriptions must match the actual item.</li>
                        <li>Prices must be fair and not misleading.</li>
                        <li>Listings are subject to review and approval by our moderation team.</li>
                        <li>You are solely responsible for completing any sale you commit to.</li>
                        <li>The Service Provider is not a contractual party to sales between users and assumes no liability for the quality, delivery, or authenticity of items traded between users.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">4. Prohibited Activities</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Users must not:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>List or sell counterfeit, proxy, fake, or reproduction cards.</li>
                        <li>Use the Platform for illegal, fraudulent, or deceptive activities.</li>
                        <li>Manipulate prices, ratings, or marketplace data.</li>
                        <li>Upload viruses, malware, or other malicious code.</li>
                        <li>Harass, abuse, threaten, or harm other users.</li>
                        <li>Send unsolicited messages or spam.</li>
                        <li>Attempt to bypass or disable Platform security or approval systems.</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Violation of these rules may result in account suspension or permanent ban.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">5. Content and Intellectual Property</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        By uploading content (including card images, descriptions, and game-related data), you grant Cards Forge a non-exclusive, worldwide, royalty-free license to use, display, and distribute that content solely for the purpose of operating and promoting the Platform.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        You retain ownership of your uploaded content and represent that:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>You have the legal right to share such content.</li>
                        <li>Your content does not infringe on third-party copyrights, trademarks, or other intellectual property rights.</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        All other materials on the Platform — including its design, structure, code, and branding — are the intellectual property of Webtech Solutions and protected under EU and Hungarian copyright law.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">6. Review and Approval Process</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        All listings are subject to automated and manual review:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>Automated checks assess listing quality and completeness.</li>
                        <li>Critical or high-value listings may require supervisor approval.</li>
                        <li>Listings that fail to meet standards may be rejected or removed.</li>
                        <li>Users will be notified about approval or rejection.</li>
                        <li>Rejected listings may be edited and resubmitted.</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        The Service Provider reserves the right to modify or remove listings that violate these Terms or applicable law.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">7. Fees and Payments</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Currently, Cards Forge does not charge listing or transaction fees.
                        We reserve the right to introduce reasonable fees in the future with prior notice to users.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        All payments and transactions between users must comply with applicable EU consumer and payment regulations.
                        Users are responsible for any taxes, customs, or fees related to their transactions.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">8. Consumer and Legal Compliance</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        This Platform operates under Hungarian and European Union law.
                        If you are a consumer residing in the EU, you are entitled to the rights and protections provided under EU consumer law (Directive 2011/83/EU and Hungarian Government Decree 45/2014).
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Cards Forge, as an intermediary, is not responsible for consumer warranty or return claims arising from user-to-user transactions.
                        Such claims must be resolved directly between the buyer and seller.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">9. Limitation of Liability</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        To the fullest extent permitted by law:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>The Service Provider shall not be liable for indirect, incidental, or consequential damages arising from Platform use.</li>
                        <li>We do not guarantee the accuracy or availability of user-generated content.</li>
                        <li>We are not responsible for disputes, losses, or fraud committed by users.</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Nothing in these Terms limits your statutory consumer rights.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">10. Termination and Suspension</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We reserve the right to suspend or terminate user accounts that violate these Terms or engage in unlawful behavior.
                        Upon termination, your access to the Platform and related data may be disabled without prior notice.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">11. Modifications to the Terms</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We may update these Terms from time to time to reflect changes in legal requirements, Platform features, or business practices.
                        Updated versions will be published on this page with a revised "Last Updated" date. Continued use of the Platform after such updates constitutes acceptance of the revised Terms.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">12. Contact Information</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        For any questions, complaints, or legal notices related to these Terms, please contact:
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <strong>{{ setting('company_name', 'Webtech Solutions') }}</strong><br>
                        <strong>Email:</strong> {{ setting('company_email', 'info@webtech-solutions.hu') }}<br>
                        <strong>Website:</strong> <a href="{{ setting('company_website', 'https://cards-forge.webtech-solutions.hu') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ setting('company_website', 'https://cards-forge.webtech-solutions.hu') }}</a>
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
