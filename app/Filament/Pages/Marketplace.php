<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\PhysicalCard;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class Marketplace extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string $view = 'filament.pages.marketplace';

    protected static ?string $navigationLabel = 'Marketplace';

    protected static ?string $title = 'Browse Marketplace';

    protected static ?int $navigationSort = 2;

    public string $search = '';
    public string $condition = '';
    public string $language = '';
    public string $minPrice = '';
    public string $maxPrice = '';
    public bool $tradeableOnly = false;
    public string $sortBy = 'latest';
    public ?int $selectedCardId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'condition' => ['except' => ''],
        'language' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'tradeableOnly' => ['except' => false],
        'sortBy' => ['except' => 'latest'],
    ];

    public function getTitle(): string | Htmlable
    {
        return 'Browse Marketplace';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Browse Marketplace';
    }

    public function getSubheading(): ?string
    {
        return 'Discover and browse physical collectible cards from verified sellers';
    }

    public function getCards()
    {
        $query = PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->with('user');

        // Search filter
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('set', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Condition filter
        if ($this->condition) {
            $query->where('condition', $this->condition);
        }

        // Price range filter
        if ($this->minPrice !== '' && is_numeric($this->minPrice)) {
            $query->where('price_per_unit', '>=', $this->minPrice);
        }
        if ($this->maxPrice !== '' && is_numeric($this->maxPrice)) {
            $query->where('price_per_unit', '<=', $this->maxPrice);
        }

        // Tradeable filter
        if ($this->tradeableOnly) {
            $query->where('tradeable', true);
        }

        // Language filter
        if ($this->language) {
            $query->where('language', $this->language);
        }

        // Sort options
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price_per_unit', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_unit', 'desc');
                break;
            case 'oldest':
                $query->oldest('approved_at');
                break;
            case 'latest':
            default:
                $query->latest('approved_at');
                break;
        }

        return $query->paginate(24);
    }

    public function getConditions()
    {
        return PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->distinct()
            ->pluck('condition')
            ->filter()
            ->sort()
            ->values();
    }

    public function getLanguages()
    {
        return PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->distinct()
            ->pluck('language')
            ->filter()
            ->sort()
            ->values();
    }

    public function applyFilters(): void
    {
        // Livewire will automatically re-render when properties change
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->condition = '';
        $this->language = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->tradeableOnly = false;
        $this->sortBy = 'latest';
    }

    public function viewCard(int $cardId): void
    {
        $this->selectedCardId = $cardId;
    }

    public function closeCardModal(): void
    {
        $this->selectedCardId = null;
    }

    public function getSelectedCard()
    {
        if ($this->selectedCardId) {
            return PhysicalCard::with('user')->find($this->selectedCardId);
        }
        return null;
    }

    public function getSimilarCards()
    {
        $card = $this->getSelectedCard();
        if (!$card) {
            return collect();
        }

        return PhysicalCard::where('status', PhysicalCard::STATUS_APPROVED)
            ->where('id', '!=', $card->id)
            ->where(function ($query) use ($card) {
                $query->where('set', $card->set)
                    ->orWhere('title', 'like', '%' . substr($card->title, 0, 10) . '%');
            })
            ->with('user')
            ->take(4)
            ->get();
    }
}
