<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace Database\Seeders;

use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Cards Forge',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'The name of your website',
                'order' => 1,
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Custom Digital Card Platform',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Tagline',
                'description' => 'A short tagline for your site',
                'order' => 2,
            ],
            [
                'key' => 'site_description',
                'value' => 'Build your custom card universe: Create cards with images and fields, design card types, assemble decks, set up games, and access everything via REST API.',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Site Description',
                'description' => 'A brief description of your website',
                'order' => 3,
            ],

            // SEO Settings
            [
                'key' => 'seo_title',
                'value' => 'Cards Forge - Custom Digital Card Platform | Create & Manage Trading Cards',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'SEO Title',
                'description' => 'Default page title for SEO',
                'order' => 10,
            ],
            [
                'key' => 'seo_description',
                'value' => 'Cards Forge - Your Customizable Digital Card Collection Hub. Create custom cards, decks, games, and access everything via REST API. Built with Laravel & Filament.',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'SEO Meta Description',
                'description' => 'Meta description for search engines',
                'order' => 11,
            ],
            [
                'key' => 'seo_keywords',
                'value' => 'cards, card games, deck builder, trading cards, card collection, custom cards, REST API, Laravel, Filament, digital cards, game creation, card types',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'SEO Keywords',
                'description' => 'Comma-separated keywords for SEO',
                'order' => 12,
            ],
            [
                'key' => 'seo_author',
                'value' => 'Webtech-Solutions',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Author',
                'description' => 'Author name for meta tags',
                'order' => 13,
            ],

            // Analytics & Tracking
            [
                'key' => 'gtm_id',
                'value' => 'G-QW8J5RJQ9C',
                'type' => 'text',
                'group' => 'analytics',
                'label' => 'Google Tag Manager ID',
                'description' => 'Your Google Analytics/GTM tracking ID (e.g., G-XXXXXXXXXX)',
                'order' => 20,
            ],
            [
                'key' => 'cookiebot_id',
                'value' => 'a8ac992a-016f-41db-8929-c9d4c51dc9a9',
                'type' => 'text',
                'group' => 'analytics',
                'label' => 'Cookiebot ID',
                'description' => 'Your Cookiebot consent management ID',
                'order' => 21,
            ],
            [
                'key' => 'analytics_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'analytics',
                'label' => 'Enable Analytics',
                'description' => 'Enable or disable analytics tracking',
                'order' => 22,
            ],

            // Social Media
            [
                'key' => 'og_image',
                'value' => '/images/og-image.png',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Open Graph Image',
                'description' => 'Image for social media sharing (1200x630px recommended)',
                'order' => 30,
            ],
            [
                'key' => 'twitter_image',
                'value' => '/images/twitter-image.png',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Twitter Card Image',
                'description' => 'Image for Twitter cards (1200x675px recommended)',
                'order' => 31,
            ],
            [
                'key' => 'twitter_handle',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Twitter Handle',
                'description' => 'Your Twitter handle (without @)',
                'order' => 32,
            ],

            // Branding
            [
                'key' => 'logo_url',
                'value' => '/images/logo.png',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Logo URL',
                'description' => 'Path to your logo image',
                'order' => 40,
            ],
            [
                'key' => 'favicon_32',
                'value' => '/favicon-32x32.png',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Favicon 32x32',
                'description' => 'Path to 32x32 favicon',
                'order' => 41,
            ],
            [
                'key' => 'favicon_16',
                'value' => '/favicon-16x16.png',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Favicon 16x16',
                'description' => 'Path to 16x16 favicon',
                'order' => 42,
            ],
            [
                'key' => 'apple_touch_icon',
                'value' => '/apple-touch-icon.png',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Apple Touch Icon',
                'description' => 'Path to Apple touch icon (180x180px)',
                'order' => 43,
            ],
            [
                'key' => 'theme_color',
                'value' => '#1e293b',
                'type' => 'text',
                'group' => 'branding',
                'label' => 'Theme Color',
                'description' => 'Browser theme color (hex code)',
                'order' => 44,
            ],

            // Legal Pages SEO
            [
                'key' => 'legal_terms_title',
                'value' => 'Terms and Conditions - Cards Forge',
                'type' => 'text',
                'group' => 'legal_seo',
                'label' => 'Terms & Conditions Page Title',
                'description' => 'SEO title for Terms and Conditions page',
                'order' => 50,
            ],
            [
                'key' => 'legal_terms_description',
                'value' => 'Terms and Conditions for Cards Forge marketplace platform. Hungarian and EU law compliant. Learn about user accounts, marketplace services, prohibited activities, and your rights.',
                'type' => 'textarea',
                'group' => 'legal_seo',
                'label' => 'Terms & Conditions Meta Description',
                'description' => 'Meta description for Terms page',
                'order' => 51,
            ],
            [
                'key' => 'legal_privacy_title',
                'value' => 'Privacy Policy - Cards Forge',
                'type' => 'text',
                'group' => 'legal_seo',
                'label' => 'Privacy Policy Page Title',
                'description' => 'SEO title for Privacy Policy page',
                'order' => 52,
            ],
            [
                'key' => 'legal_privacy_description',
                'value' => 'GDPR-compliant Privacy Policy for Cards Forge. Learn how we collect, use, and protect your personal data in accordance with Hungarian and European Union law.',
                'type' => 'textarea',
                'group' => 'legal_seo',
                'label' => 'Privacy Policy Meta Description',
                'description' => 'Meta description for Privacy page',
                'order' => 53,
            ],

            // Company Information
            [
                'key' => 'company_name',
                'value' => 'Webtech Solutions',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Name',
                'description' => 'Legal company name',
                'order' => 60,
            ],
            [
                'key' => 'company_email',
                'value' => 'info@webtech-solutions.hu',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Email',
                'description' => 'Primary company contact email',
                'order' => 61,
            ],
            [
                'key' => 'company_website',
                'value' => 'https://cards-forge.webtech-solutions.hu',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Website',
                'description' => 'Full company website URL',
                'order' => 62,
            ],
            [
                'key' => 'company_country',
                'value' => 'Hungary',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Country',
                'description' => 'Country of operation',
                'order' => 63,
            ],
        ];

        foreach ($settings as $setting) {
            WebsiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
