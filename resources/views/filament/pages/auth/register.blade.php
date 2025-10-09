<x-filament-panels::page.simple>
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
