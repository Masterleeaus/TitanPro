<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'assigned_to');
    }

    public function hasConfirmedTwoFactor(): bool
    {
        return ! empty($this->two_factor_confirmed_at)
            || ! empty($this->two_factor_secret)
            || ! empty($this->two_factor_enabled_at);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'titansolo') {
            return $this->hasRole('owner')
                && in_array($this->organization?->plan, ['starter', 'solo', 'single_operator'], true);
        }

        $panelRoles = config("titan_panels.panels.{$panel->getId()}.roles");

        if (is_array($panelRoles) && count($panelRoles) > 0) {
            return $this->hasRole($panelRoles);
        }

        return $this->hasRole(['super_admin', 'admin', 'owner']);
    }
    public function hasConfirmedTwoFactor(): bool
    {
        return ! empty($this->two_factor_confirmed_at)
            || ! empty($this->two_factor_secret)
            || ! empty($this->two_factor_enabled_at);
    }


    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return $this->hasConfirmedTwoFactor();
    }

    public function hasTwoFactorEnabled(): bool
    {
        return $this->hasConfirmedTwoFactor();
    }

}
