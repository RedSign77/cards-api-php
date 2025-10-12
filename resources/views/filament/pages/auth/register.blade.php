<x-filament-panels::page.simple>
    <!-- Beta Notice Banner -->
    <div class="mb-6 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg p-4 shadow-lg">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-1">Beta Version - Approval Required</h3>
                <p class="text-sm leading-relaxed">
                    This platform is currently in beta testing. After registration, your account will need to be approved by a supervisor before you can log in and use the platform for the first time. You will receive an email notification once your account is approved.
                </p>
            </div>
        </div>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_REGISTER_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form wire:submit="register" id="register-form">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_REGISTER_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    @push('scripts')
        <script src="https://www.google.com/recaptcha/enterprise.js?render={{ config('recaptcha.site_key') }}"></script>
        <script>
            let recaptchaExecuted = false;

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('register-form');

                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (!recaptchaExecuted) {
                            e.preventDefault();
                            e.stopPropagation();

                            grecaptcha.enterprise.ready(function() {
                                grecaptcha.enterprise.execute('{{ config('recaptcha.site_key') }}', {action: 'register'})
                                    .then(function(token) {
                                        recaptchaExecuted = true;
                                        @this.set('data.g-recaptcha-response', token);

                                        // Trigger form submission after setting token
                                        setTimeout(() => {
                                            form.querySelector('button[type="submit"]').click();
                                        }, 100);
                                    });
                            });

                            return false;
                        }
                    });
                }
            });
        </script>
    @endpush
</x-filament-panels::page.simple>
