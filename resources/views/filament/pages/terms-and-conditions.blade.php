<x-filament-panels::page>
    <div class="prose dark:prose-invert max-w-none">
        <h1 class="text-3xl font-bold mb-6">Terms and Conditions</h1>

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            <strong>Last Updated:</strong> {{ date('F d, Y', filemtime(resource_path('views/filament/pages/terms-and-conditions.blade.php'))) }}
        </p>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">1. Acceptance of Terms</h2>
            <p class="mb-4">
                By accessing and using Cards Forge ("the Platform"), you accept and agree to be bound by the terms
                and provision of this agreement. If you do not agree to these Terms and Conditions, please do not
                use this Platform.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">2. User Accounts</h2>
            <p class="mb-4">
                To access certain features of the Platform, you must register for an account. You agree to:
            </p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>Provide accurate, current, and complete information during registration</li>
                <li>Maintain the security of your password and account</li>
                <li>Notify us immediately of any unauthorized use of your account</li>
                <li>Accept responsibility for all activities that occur under your account</li>
            </ul>
            <p class="mb-4">
                Your account requires email verification and supervisor approval before full access is granted.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">3. Physical Card Marketplace</h2>
            <p class="mb-4">
                Cards Forge provides a marketplace for buying, selling, and trading physical cards. By listing cards
                for sale, you agree that:
            </p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>All card listings must be accurate and truthful</li>
                <li>Card condition descriptions must match the actual condition</li>
                <li>Pricing must be reasonable and not misleading</li>
                <li>All listings are subject to review and approval by supervisors</li>
                <li>We reserve the right to reject any listing that violates these terms</li>
                <li>You are responsible for fulfilling all sales you agree to</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">4. Prohibited Activities</h2>
            <p class="mb-4">
                You agree not to:
            </p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
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
            <h2 class="text-2xl font-semibold mb-4">5. Content and Intellectual Property</h2>
            <p class="mb-4">
                By uploading content (including card images, descriptions, and game data), you grant Cards Forge
                a non-exclusive, worldwide, royalty-free license to use, display, and distribute your content on
                the Platform.
            </p>
            <p class="mb-4">
                You retain ownership of your content and are responsible for ensuring you have the right to upload it.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">6. Review and Approval Process</h2>
            <p class="mb-4">
                All card listings are subject to automated and manual review:
            </p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>Listings are automatically evaluated based on quality criteria</li>
                <li>Critical listings require supervisor approval</li>
                <li>We may reject listings that don't meet our standards</li>
                <li>You will be notified of approval or rejection decisions</li>
                <li>Rejected listings may be edited and resubmitted</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">7. Fees and Payments</h2>
            <p class="mb-4">
                Currently, Cards Forge does not charge listing fees. We reserve the right to introduce fees in the
                future with reasonable notice to users.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">8. Termination</h2>
            <p class="mb-4">
                We reserve the right to suspend or terminate your account at any time for:
            </p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>Violation of these Terms and Conditions</li>
                <li>Fraudulent or illegal activity</li>
                <li>Abuse of the Platform or other users</li>
                <li>Any reason we deem necessary to protect the Platform</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">9. Limitation of Liability</h2>
            <p class="mb-4">
                Cards Forge is provided "as is" without warranties of any kind. We are not responsible for:
            </p>
            <ul class="list-disc pl-6 mb-4 space-y-2">
                <li>Disputes between buyers and sellers</li>
                <li>Loss or damage to cards during shipping</li>
                <li>Accuracy of user-generated content</li>
                <li>Service interruptions or data loss</li>
                <li>Any damages arising from use of the Platform</li>
            </ul>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">10. Changes to Terms</h2>
            <p class="mb-4">
                We reserve the right to modify these Terms and Conditions at any time. Users will be notified of
                significant changes via email. Continued use of the Platform after changes constitutes acceptance
                of the new terms.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">11. Governing Law</h2>
            <p class="mb-4">
                These Terms and Conditions are governed by and construed in accordance with applicable laws.
                Any disputes shall be resolved in the appropriate courts.
            </p>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">12. Contact Information</h2>
            <p class="mb-4">
                If you have any questions about these Terms and Conditions, please contact us at:
            </p>
            <p class="mb-4">
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
</x-filament-panels::page>
