<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->id('invite_id');

            $table->string('invite_email');
            $table->enum('invite_status', ['accepted', 'pending']);
            $table->string('invite_link_ref')->unique();
            $table->foreignId('invite_sent_by')->constrained('users', 'id');

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('invites');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
