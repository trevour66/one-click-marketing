<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\emailMarketingLink;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $appends = [
        'permission'
    ];

    protected $with =[
        'permissions',
        'roles'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_unique_public_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the emailMarketingLinks for the blog post.
     */
    public function emailMarketingLinks(): HasMany
    {
        return $this->hasMany(emailMarketingLink::class, 'user_unique_public_id', 'user_unique_public_id');
    }

    public function getPermissionAttribute(){
        return $this->getAllPermissions();
    }

    /**
     * Get the employee associated with the user.
     */
    public function invite(): HasMany {
        return $this->hasMany(Invite::class, 'invite_sent_by');
    }

}
