<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id(); // ده بيكافئ bigint unsigned not null primary key AUTO_INCREMENT
            $table->string('label', 45)->nullable(); // varchar(45) null
            $table->string('link', 255)->nullable(false); // varchar(255) not null
            $table->string('icon', 45)->nullable(); // varchar(45) null
            $table->unsignedBigInteger('admin_id'); // bigint unsigned not null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
