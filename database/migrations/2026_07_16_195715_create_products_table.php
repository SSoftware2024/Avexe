<?php

use App\Models\Company;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('value', 6,2);
            $table->boolean('is_display')->default(true);
            $table->string('photo')->nullable();
            $table->decimal('score_value', 6,4)->default(0);
            $table->double('commission_value', 6,2)->nullable();
            $table->double('is_commission_value_percentage')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(Company::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
