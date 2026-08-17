<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    public function hotel(): HasMany{
        return $this->hasMany(Hotel::class, 'owner_id');
    }

    public function booking(): HasMany{
        return $this->hasMany(Booking::class);
    }

    public function review(): HasMany{
        return $this->hasMany(Review::class);
    }

    public function favorite(): HasMany{
        return $this->hasMany(Favorites::class);
    }

    public function favorite_hotel():BelongsToMany {
        return $this->belongsToMany(Hotel::class, 'favorites');
    }

    public function support_ticket():HasMany {
        return $this->hasMany(SupportTickets::class);
    }

    public function userType(): BelongsTo {
        return $this->belongsTo(UserType::class);
    }

    public function roles():BelongsToMany{
        return $this->belongsToMany(Role::class);
    }

    // Each users ko roles haru check garna ko lagi function...
    public function hasRole(String $role):bool{
        return $this->roles()->where('name',$role)->exists();
    }

    //Each user ko permissions haru check garna ko lagi function...
    public function hasPermission(String $permission):bool{
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission){
                $query->where('name', $permission);
            })
            ->exists();
    }

    //Each user ko Kun kun roles haru assigned vhako cha herna lai function
    public function hasAnyRole(array $roles):bool{
        return $this->roles()
            ->whereIn('name', $roles)
            ->exists();
    }

    //Each user ko kun kun permissions haru assigned vako cha herna lai funcition
    public function hasAnyPermission(array $permissions){
        return $this->roles()
            ->whereHas('permissions', function($query) use ($permissions){
                $query->whereIn('name', $permissions);
            })->exists();
    }
}
