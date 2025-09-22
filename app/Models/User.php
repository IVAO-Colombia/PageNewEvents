<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser , HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'vid_ivao',
        'division_id',
        'country_id',
        'pilotRating_name',
        'pilotRating_short',
        'rank_ivao',
        'email',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->fullName())
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /** Name Complete */
    public function fullName(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /** Accessor for the user's full name */
    public function getFilamentName(): string
    {
         return trim("{$this->firstname} {$this->lastname}");
    }

    public function hasPanelAccess(): bool
    {
        $adminPermitions = ['CO-EC','CO-EAC','CO-EA1','CO-WM','CO-AWM','CO-WMA1','CO-DIR','CO-ADIR'];
        return in_array($this->rank_ivao, $adminPermitions);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasPanelAccess();
    }
}
