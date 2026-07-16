<?php

use App\Models\Company;
use App\Models\EmployeeShift;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('profile_picture')->nullable();
            $table->boolean('is_activeted')->default(true);
            $table->double('monthly_amount', 6,2)->nullable(); #salário mensal
            $table->boolean('is_comission')->default(true); #recebe por comissão
            $table->double('commission_value', 6,2)->nullable();
            $table->double('is_commission_value_percentage')->nullable();
            $table->boolean('is_activeted')->default(true);
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(EmployeeShift::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
