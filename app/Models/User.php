<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewUserRegistered;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'supervisor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'supervisor' => 'boolean',
        ];
    }

    /**
     * Boot method to register model events
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            // Send notification to admin email about new user registration
            Notification::route('mail', 'info@webtech-solutions.hu')
                ->notify(new NewUserRegistered($user));
        });
    }

    /**
     * Fix production issue
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    /**
     * Get Avatar URL
     *
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');
        return $this->$avatarColumn ? Storage::url($this->$avatarColumn) : null;
    }

    /**
     * Get all cards created by this user
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get all card types created by this user
     */
    public function cardTypes()
    {
        return $this->hasMany(CardType::class);
    }

    /**
     * Get all games created by this user
     */
    public function games()
    {
        return $this->hasMany(Game::class, 'creator_id');
    }

    /**
     * Get all decks created by this user
     */
    public function decks()
    {
        return $this->hasMany(Deck::class, 'creator_id');
    }

    /**
     * Get all hexas created by this user (through games)
     */
    public function hexas()
    {
        return Hexa::whereHas('game', function ($query) {
            $query->where('creator_id', $this->id);
        });
    }

    /**
     * Get all figures created by this user (through games)
     */
    public function figures()
    {
        return Figure::whereHas('game', function ($query) {
            $query->where('creator_id', $this->id);
        });
    }

    /**
     * Check if user is a supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->supervisor === true;
    }
}
