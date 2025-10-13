<x-filament-panels::page.simple>
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_PASSWORD_RESET_RESET_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form wire:submit="resetPassword">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_PASSWORD_RESET_RESET_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    @push('styles')
        <style>
            /* Cards Forge Auth Page Styling */
            .fi-simple-page {
                background: #f8fafc;
                min-height: 100vh;
            }

            .fi-simple-main {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
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
