<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - Cards Forge</title>
    <meta name="description" content="Privacy Policy for Cards Forge marketplace platform">
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
                <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Privacy Policy</h1>

                <p class="text-sm text-gray-600 dark:text-gray-400 mb-8">
                    <strong>Last Updated:</strong> {{ date('F d, Y') }}
                </p>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">1. Introduction</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        Cards Forge ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy
                        explains how we collect, use, disclose, and safeguard your information when you use our platform.
                    </p>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        By using Cards Forge, you agree to the collection and use of information in accordance with this policy.
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
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">3. How We Use Your Information</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We use the collected information for:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Account Management:</strong> Creating and managing your account</li>
                        <li><strong>Email Verification:</strong> Confirming your email address</li>
                        <li><strong>Account Approval:</strong> Supervisor review of new registrations</li>
                        <li><strong>Marketplace Operations:</strong> Processing and displaying card listings</li>
                        <li><strong>Notifications:</strong> Sending updates about your listings and account status</li>
                        <li><strong>Security:</strong> Detecting and preventing fraud, abuse, and unauthorized access</li>
                        <li><strong>Analytics:</strong> Understanding platform usage and improving our services</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">4. Data Storage and Security</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        We implement appropriate technical and organizational security measures to protect your data:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Encryption:</strong> Passwords are encrypted using industry-standard hashing</li>
                        <li><strong>Secure Storage:</strong> Data is stored on secure servers</li>
                        <li><strong>Access Control:</strong> Limited access to personal information</li>
                        <li><strong>SSL/TLS:</strong> Encrypted communication between your browser and our servers</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">5. Your Rights</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        You have the right to:
                    </p>
                    <ul class="list-disc pl-6 mb-4 space-y-2 text-gray-700 dark:text-gray-300">
                        <li><strong>Access:</strong> Request a copy of your personal data</li>
                        <li><strong>Correction:</strong> Update or correct your information via your profile</li>
                        <li><strong>Deletion:</strong> Request deletion of your account and data</li>
                        <li><strong>Data Portability:</strong> Request your data in a portable format</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">6. Contact Us</h2>
                    <p class="mb-4 text-gray-700 dark:text-gray-300">
                        If you have questions about this Privacy Policy or our data practices, please contact us:
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
