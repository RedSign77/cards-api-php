<?php

/*
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers;

use Illuminate\Support\Str;

class ChangelogController extends Controller
{
    /**
     * Display the changelog page
     */
    public function index()
    {
        $changelogPath = base_path('CHANGELOG.md');

        if (!file_exists($changelogPath)) {
            abort(404, 'Changelog not found');
        }

        $content = file_get_contents($changelogPath);

        // Parse changelog into versions
        $versions = $this->parseChangelog($content);

        return view('changelog', [
            'versions' => $versions,
            'currentVersion' => config('app_config.version'),
            'versionDate' => config('app_config.version_date'),
        ]);
    }

    /**
     * Parse markdown changelog into structured data
     */
    protected function parseChangelog(string $content): array
    {
        $versions = [];
        $lines = explode("\n", $content);
        $currentVersion = null;
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);

            // Match version headers: ## [1.1.0] - 2025-12-24
            if (preg_match('/^##\s+\[([^\]]+)\](?:\s*-\s*(.+))?/', $line, $matches)) {
                if ($currentVersion) {
                    $versions[] = $currentVersion;
                }

                $currentVersion = [
                    'number' => $matches[1],
                    'date' => $matches[2] ?? null,
                    'sections' => [],
                ];
                $currentSection = null;
                continue;
            }

            // Match section headers: ### Added, ### Changed, etc.
            if (preg_match('/^###\s+(.+)/', $line, $matches)) {
                $sectionName = trim($matches[1]);
                $currentSection = $sectionName;

                if ($currentVersion && !isset($currentVersion['sections'][$sectionName])) {
                    $currentVersion['sections'][$sectionName] = [];
                }
                continue;
            }

            // Match list items: - Item text
            if (preg_match('/^-\s+(.+)/', $line, $matches)) {
                if ($currentVersion && $currentSection) {
                    $currentVersion['sections'][$currentSection][] = trim($matches[1]);
                }
                continue;
            }
        }

        // Add the last version
        if ($currentVersion) {
            $versions[] = $currentVersion;
        }

        return $versions;
    }
}
