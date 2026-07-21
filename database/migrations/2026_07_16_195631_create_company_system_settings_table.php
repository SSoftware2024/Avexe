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
        Schema::create('company_system_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('simultaneous_access')->default(1); #numero de acessos simultaneos
            $table->integer('max_owners')->default(1); #numero de acessos simultaneos
            $table->integer('max_employees')->default(1); #numero de acessos simultaneos
            $table->date('date_payment')->default(now()->addMonths(1)); #data pagamento
            $table->smallInteger('interval_return_message')->nullable(); #intervalo em dias para mensagem de retorno
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
        Schema::dropIfExists('company_system_settings');
    }
};
