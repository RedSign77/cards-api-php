<x-filament-panels::page.simple>
    <x-slot name="heading">
        @if(config('app_version.status') !== 'stable')
            <div style="background: linear-gradient(to right, #f59e0b, #ea580c); color: #ffffff; padding: 0.75rem 1rem; text-align: center; font-size: 0.875rem; font-weight: 600; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <svg style="display: inline-block; width: 1.25rem; height: 1.25rem; vertical-align: middle; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ strtoupper(config('app_version.status')) }} VERSION - Registration requires supervisor approval before first use
            </div>
        @endif
        {{ __('filament-panels::pages/auth/register.title') }}
    </x-slot>

    @if (filament()->hasLogin())
        <x-slot name="subheading">
            Already have an account?

            {{ $this->loginAction }}
        </x-slot>
    @endif

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

    @push('styles')
        <style>
            /* Cards Forge Auth Page Styling */
            .fi-simple-page {
                background: #f8fafc;
                min-height: auto !important;
                padding: 3rem 0 !important;
            }

            .fi-simple-layout {
                min-height: auto !important;
            }

            .fi-simple-main {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            /* Card suit decorations */
            .fi-simple-main::before {
                content: '♠ ♥ ♣ ♦';
                display: block;
                text-align: center;
                font-size: 1.5rem;
                color: #cbd5e1;
                letter-spacing: 1rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            /* Heading styling */
            .fi-simple-header-heading {
                color: #1e293b;
                font-weight: 700;
            }

            /* Submit button */
            .fi-btn-primary {
                background: #ef4444 !important;
                border: none !important;
                font-weight: 600 !important;
                letter-spacing: 0.025em !important;
                transition: all 0.3s ease !important;
            }

            .fi-btn-primary:hover {
                background: #dc2626 !important;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            }

            /* Links */
            .fi-link {
                color: #ef4444 !important;
            }

            .fi-link:hover {
                color: #dc2626 !important;
            }
        </style>
    @endpush
</x-filament-panels::page.simple>
