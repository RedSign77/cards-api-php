<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
use App\Notifications\UserEmailConfirmed;

class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
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
            'approved_at' => 'datetime',
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

        static::updated(function (User $user) {
            // Send notification to all supervisors when user confirms email
            if ($user->wasChanged('email_verified_at') && $user->email_verified_at !== null && $user->approved_at === null) {
                $supervisors = User::where('supervisor', true)->get();
                foreach ($supervisors as $supervisor) {
                    $supervisor->notify(new UserEmailConfirmed($user));
                }
            }
        });
    }

    /**
     * Check if user can access Filament panel
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Supervisors can always access
        if ($this->supervisor) {
            return true;
        }

        // Regular users need email verified and supervisor approval
        return $this->hasVerifiedEmail() && $this->approved_at !== null;
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
