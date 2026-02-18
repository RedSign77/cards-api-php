<div class="space-y-6 p-2">

    {{-- Score + Archetype --}}
    <div class="flex items-center gap-4">
        <div class="text-center">
            <div class="text-4xl font-bold text-primary-600">{{ $report['overall_score'] ?? '—' }}</div>
            <div class="text-xs text-gray-500 mt-1">Overall Score / 100</div>
        </div>
        <div>
            <span class="inline-flex items-center rounded-full bg-primary-100 px-3 py-1 text-sm font-medium text-primary-800">
                {{ $report['archetype'] ?? 'Unknown' }}
            </span>
            <div class="text-xs text-gray-500 mt-1">Archetype</div>
        </div>
        <div class="ml-auto">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                {{ ($report['synergy_report']['rating'] ?? '') === 'High' ? 'bg-success-100 text-success-800' : (($report['synergy_report']['rating'] ?? '') === 'Medium' ? 'bg-warning-100 text-warning-800' : 'bg-danger-100 text-danger-800') }}">
                Synergy: {{ $report['synergy_report']['rating'] ?? '—' }}
            </span>
        </div>
    </div>

    {{-- Win Rate --}}
    @if(!empty($report['win_rate_prediction']))
    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 px-4 py-3 text-sm">
        <span class="font-semibold">Estimated Win Rate:</span> {{ $report['win_rate_prediction'] }}
    </div>
    @endif

    {{-- Curve Analysis --}}
    @if(!empty($report['curve_analysis']))
    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cost Curve Analysis</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $report['curve_analysis'] }}</p>
    </div>
    @endif

    {{-- Key Combos --}}
    @if(!empty($report['synergy_report']['key_combos']))
    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Key Combos</h3>
        <ul class="space-y-1">
            @foreach($report['synergy_report']['key_combos'] as $combo)
            <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                <span class="text-success-500 mt-0.5">✓</span>
                <span>{{ $combo }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Anti-Synergies --}}
    @if(!empty($report['synergy_report']['anti_synergies']))
    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Anti-Synergies</h3>
        <ul class="space-y-1">
            @foreach($report['synergy_report']['anti_synergies'] as $item)
            <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                <span class="text-warning-500 mt-0.5">⚠</span>
                <span>{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Weaknesses --}}
    @if(!empty($report['weaknesses']))
    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Weaknesses</h3>
        <ul class="space-y-1">
            @foreach($report['weaknesses'] as $weakness)
            <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                <span class="text-danger-500 mt-0.5">✗</span>
                <span>{{ $weakness }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Optimization Suggestions --}}
    @if(!empty($report['optimization_suggestions']))
    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Optimization Suggestions</h3>
        <div class="space-y-2">
            @foreach($report['optimization_suggestions'] as $suggestion)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3 text-sm">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-danger-600 font-medium">Remove: {{ $suggestion['remove'] ?? '—' }}</span>
                    <span class="text-gray-400">→</span>
                    <span class="text-success-600 font-medium">Add: {{ $suggestion['replace_with'] ?? '—' }}</span>
                </div>
                <p class="text-gray-500 dark:text-gray-400">{{ $suggestion['reason'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
