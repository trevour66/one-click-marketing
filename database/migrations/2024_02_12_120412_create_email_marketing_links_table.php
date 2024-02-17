<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_marketing_links', function (Blueprint $table) {
            $table->id('email_marketing_links_id');
            $table->string('success_page_url');
            $table->string('failure_page_url');
            $table->string('name')->unique();
            $table->string('campaign');
            $table->string('zapier_webhook_url');
            $table->string('link_identifier')->unique();
            $table->string('full_link')->unique();
            $table->foreignId('email_marketing_platforms_id')->constrained('email_marketing_platforms', 'email_marketing_platforms_id');
            $table->string('user_unique_public_id')->constrained('users', 'user_unique_public_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_marketing_links');
    }
};
