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
        Schema::create('dropbox_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('code',50);
            $table->string('client_id',50)->comment('dropbox app key');
            $table->string('client_secret',50)->comment('dropbox app secret key');
            $table->string('app_redirect_url')->comment('once user authorized their account in browser, code will be send to this url');
            $table->text('refresh_token')->nullable(true);
            $table->text('access_token')->nullable(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dropbox_credentials');
    }
};
