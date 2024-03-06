<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

use App\Events\NewUserInviteCreated;

class Invite extends Model
{
    use HasFactory, Notifiable;


    protected $primaryKey = 'invite_id';

    protected $dispatchesEvents = [
        'created' => NewUserInviteCreated::class,
        'updated' => NewUserInviteCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invite_email',
        'invite_status',
        'invite_link_ref',
        'invite_sent_by',
    ];

     public function routeNotificationForMail(Notification $notification): array|string
    {
        // Return email address only...
        return $this->invite_email;
 
    }
}
