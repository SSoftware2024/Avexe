<?php

use App\Models\Appointment;
use App\Models\Company;
use App\Models\Product;
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
        Schema::create('consumption_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(Appointment::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumption_customers');
    }
};
