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
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_zh')->nullable();
            $table->string('open_status')->nullable();
            $table->string('introduction')->nullable();
            $table->string('open_time')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('distric')->nullable();
            $table->string('address')->nullable();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('months')->nullable();
            $table->float('nlat')->nullable();;
            $table->float('elong')->nullable();;
            $table->string('official_site')->nullable();
            $table->string('facebook')->nullable();
            $table->string('ticket')->nullable();
            $table->string('remind')->nullable();
            $table->string('stay_time')->nullable();
            $table->string('modified')->nullable();
            $table->string('url')->nullable();
            $table->string('target')->nullable();
            $table->string('service')->nullable();
            $table->string('friendly')->nullable();
            $table->string('images')->nullable();
            $table->string('files')->nullable();
            $table->string('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};
