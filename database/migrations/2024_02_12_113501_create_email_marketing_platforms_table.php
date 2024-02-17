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
        Schema::create('email_marketing_platforms', function (Blueprint $table) {
            $table->id('email_marketing_platforms_id');
            $table->string('name')->unique();
            $table->string('merge_tag_email');
            $table->string('merge_tag_firstname');
            $table->string('merge_tag_lastname');            
            $table->timestamps();
        });

        DB::table('email_marketing_platforms')->insert([
            'name' => 'MailChimp',
            'merge_tag_email' => '*|EMAIL|*',
            'merge_tag_firstname' => '*|FNAME|*',
            'merge_tag_lastname' => '*|LNAME|*',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'ConvertKit',
            'merge_tag_email' => '{{ subscriber.email_address }}',
            'merge_tag_firstname' => '{{ subscriber.first_name }}',
            'merge_tag_lastname' => '{{ subscriber.last_name }}',            
            'created_at' => now(),
            'updated_at' => now()
        ]);       
        
        DB::table('email_marketing_platforms')->insert([
            'name' => 'Kajabi',
            'merge_tag_email' => '{{email}}',
            'merge_tag_firstname' => '{{first_name}}',
            'merge_tag_lastname' => '{{last_name}}',            
            'created_at' => now(),
            'updated_at' => now()
        ]);   

        DB::table('email_marketing_platforms')->insert([
            'name' => 'ActiveCampaign',
            'merge_tag_email' => '%EMAIL%',
            'merge_tag_firstname' => '%FIRSTNAME%',
            'merge_tag_lastname' => '%LASTNAME%',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'Flodesk',
            'merge_tag_email' => '{{ subscriber.email_address }}',
            'merge_tag_firstname' => '{{ subscriber.first_name }}',
            'merge_tag_lastname' => '{{ subscriber.last_name }}',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'Drip',
            'merge_tag_email' => '{{ subscriber.email }}',
            'merge_tag_firstname' => '{{ subscriber.first_name }}',
            'merge_tag_lastname' => '{{ subscriber.last_name }}',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'GoHighLevel',
            'merge_tag_email' => '{{contact.email}}',
            'merge_tag_firstname' => '{{contact.first_name}}',
            'merge_tag_lastname' => '{{contact.last_name}}',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'Ontraport',
            'merge_tag_email' => '[E-Mail]',
            'merge_tag_firstname' => '[First Name]',
            'merge_tag_lastname' => '[Last Name]',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'Keap',
            'merge_tag_email' => '~Contact.Email~',
            'merge_tag_firstname' => '~Contact.FirstName~',
            'merge_tag_lastname' => '~Contact.LastName~',            
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('email_marketing_platforms')->insert([
            'name' => 'MailerLite',
            'merge_tag_email' => '{$email}',
            'merge_tag_firstname' => '{$name}',
            'merge_tag_lastname' => '{$last_name}',            
            'created_at' => now(),
            'updated_at' => now()
        ]);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_marketing_platforms');
    }
};
