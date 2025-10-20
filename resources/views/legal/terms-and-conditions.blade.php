<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms and Conditions - Cards Forge</title>
    <meta name="description" content="Terms and Conditions for Cards Forge marketplace platform">
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
                <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Terms and Conditions</h1>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-8">
                    <strong>Last Updated:</strong> {{ date('F d, Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">1. Acceptance of Terms</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        By accessing and using Cards Forge ("the Platform"), you accept and agree to be bound by the terms
                        and provision of this agreement. If you do not agree to these Terms and Conditions, please do not
                        use this Platform.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">2. User Accounts</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        To access certain features of the Platform, you must register for an account. You agree to:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>Provide accurate, current, and complete information during registration</li>
                        <li>Maintain the security of your password and account</li>
                        <li>Notify us immediately of any unauthorized use of your account</li>
                        <li>Accept responsibility for all activities that occur under your account</li>
                    </ul>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Your account requires email verification and supervisor approval before full access is granted.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">3. Physical Card Marketplace</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Cards Forge provides a marketplace for buying, selling, and trading physical cards. By listing cards
                        for sale, you agree that:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>All card listings must be accurate and truthful</li>
                        <li>Card condition descriptions must match the actual condition</li>
                        <li>Pricing must be reasonable and not misleading</li>
                        <li>All listings are subject to review and approval by supervisors</li>
                        <li>We reserve the right to reject any listing that violates these terms</li>
                        <li>You are responsible for fulfilling all sales you agree to</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">4. Prohibited Activities</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        You agree not to:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>List counterfeit, proxy, fake, or reproduction cards</li>
                        <li>Use the Platform for any illegal purposes</li>
                        <li>Attempt to circumvent any security features</li>
                        <li>Harass, abuse, or harm other users</li>
                        <li>Spam or send unsolicited communications</li>
                        <li>Manipulate prices or engage in fraudulent activities</li>
                        <li>Upload malicious code or viruses</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">5. Content and Intellectual Property</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        By uploading content (including card images, descriptions, and game data), you grant Cards Forge
                        a non-exclusive, worldwide, royalty-free license to use, display, and distribute your content on
                        the Platform.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        You retain ownership of your content and are responsible for ensuring you have the right to upload it.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">6. Review and Approval Process</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        All card listings are subject to automated and manual review:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li>Listings are automatically evaluated based on quality criteria</li>
                        <li>Critical listings require supervisor approval</li>
                        <li>We may reject listings that don't meet our standards</li>
                        <li>You will be notified of approval or rejection decisions</li>
                        <li>Rejected listings may be edited and resubmitted</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">7. Fees and Payments</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Currently, Cards Forge does not charge listing fees. We reserve the right to introduce fees in the
                        future with reasonable notice to users.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">8. Contact Information</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        If you have any questions about these Terms and Conditions, please contact us at:
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        <strong>Email:</strong> {{ config('mail.admin_address', 'admin@cardsforge.com') }}<br>
                        <strong>Website:</strong> {{ config('app.url') }}
                    </p>
                </section>

                <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        © {{ date('Y') }} Cards Forge. All rights reserved. Webtech-solutions.
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
