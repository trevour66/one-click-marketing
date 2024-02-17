<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class emailMarketingLink extends Model
{
    use HasFactory;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "email_marketing_links_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'failure_page_url',
        'success_page_url',
        'link_identifier',
        'full_link',
        'email_marketing_platforms_id',
        'campaign',
        'zapier_webhook_url',
        'user_unique_public_id'
    ];
}
