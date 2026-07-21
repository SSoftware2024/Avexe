<?php

use App\Models\Company;
use App\Models\User;
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
        Schema::create('notification_systems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_sended_by_company')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(User::class)->constrained(); #cliente destinatário mensagem
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_systems');
    }
};
