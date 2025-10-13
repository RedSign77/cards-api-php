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
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
                min-height: 100vh;
            }

            .fi-simple-main {
                background: linear-gradient(to bottom, rgba(30, 41, 59, 0.95), rgba(15, 23, 42, 0.95));
                border: 1px solid #334155;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 6px 10px rgba(0, 0, 0, 0.2);
            }

            /* Card suit decorations */
            .fi-simple-main::before {
                content: '♠ ♥ ♣ ♦';
                display: block;
                text-align: center;
                font-size: 1.5rem;
                color: #475569;
                letter-spacing: 1rem;
                margin-bottom: 1rem;
                opacity: 0.3;
            }

            /* Heading styling */
            .fi-simple-header-heading {
                background: linear-gradient(to right, #ef4444, #dc2626);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-weight: 700;
            }

            /* Form inputs */
            .fi-input {
                background-color: rgba(15, 23, 42, 0.5) !important;
                border-color: #334155 !important;
                color: #e2e8f0 !important;
            }

            .fi-input:focus {
                border-color: #ef4444 !important;
                ring-color: rgba(239, 68, 68, 0.2) !important;
            }

            /* Submit button */
            .fi-btn-primary {
                background: linear-gradient(to right, #ef4444, #dc2626) !important;
                border: none !important;
                font-weight: 600 !important;
                letter-spacing: 0.025em !important;
                transition: all 0.3s ease !important;
            }

            .fi-btn-primary:hover {
                background: linear-gradient(to right, #dc2626, #b91c1c) !important;
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

            /* Labels */
            .fi-fo-field-wrp-label label {
                color: #cbd5e1 !important;
            }
        </style>
    @endpush
</x-filament-panels::page.simple>
