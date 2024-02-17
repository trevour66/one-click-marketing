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
        Schema::create('email_marketing_stats', function (Blueprint $table) {
            $table->id('email_marketing_stats_id');

            $table->string('email');
            $table->string('firstname');
            $table->string('lastname');

            $table->foreignId('email_marketing_links_id')->constrained('email_marketing_links', 'email_marketing_links_id');

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
        Schema::dropIfExists('email_marketing_stats');
    }
};
