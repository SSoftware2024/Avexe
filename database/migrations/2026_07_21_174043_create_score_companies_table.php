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
        Schema::create('score_companies', function (Blueprint $table) {
            $table->id();
            $table->integer('score')->default(0);
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(User::class)->constrained(); //cliente na lista
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('score_companies');
    }
};
