<div class="fi-footer border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
    <div class="fi-container mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Copyright & Legal Links -->
            <div class="text-sm text-gray-600 dark:text-gray-400 text-center sm:text-left">
                <div>
                    © {{ date('Y') }} <a href="https://webtech-solutions.hu" target="_blank" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Webtech-solutions</a>, All rights reserved.
                </div>
                <div class="mt-1 space-x-3">
                    <a href="{{ \App\Filament\Pages\TermsAndConditions::getUrl() }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        Terms & Conditions
                    </a>
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <a href="{{ \App\Filament\Pages\PrivacyPolicy::getUrl() }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        Privacy Policy
                    </a>
                </div>
            </div>

            <!-- Version Badge -->
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                    {{ config('app_version.status') === 'beta' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                    @if(config('app_version.status') === 'beta')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @endif
                    <span>{{ strtoupper(config('app_version.status')) }}</span>
                    <span class="ml-1">{{ config('app_version.version') }}</span>
                </span>
            </div>
        </div>
    </div>
</div>
