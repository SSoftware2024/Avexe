<?php

use App\Enum\DayOfWeek;
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
        Schema::create('company_operating_hours', function (Blueprint $table) {
            $table->id();
            // Dias da semana e status ativo
            $table->enum('day_of_week', DayOfWeek::toArrayValues());
            $table->boolean('is_active')->default(true); // # ativo: Define se o horário está ativo/funcionando

            // Turno da Manhã
            $table->time('morning_start_time')->nullable(); // # manha_horario_inicio: Horário de início do turno da manhã
            $table->time('morning_end_time')->nullable();   // # manha_horario_fim: Horário de término do turno da manhã

            // Turno da Tarde 
            $table->time('afternoon_start_time')->nullable(); // # tarde_horario_inicio: Horário de início do turno da tarde
            $table->time('afternoon_end_time')->nullable();   // # tarde_horario_fim: Horário de término do turno da tarde

            // Turno da Noite 
            $table->time('evening_start_time')->nullable(); // # noite_horario_inicio: Horário de início do turno da noite
            $table->time('evening_end_time')->nullable();   // # noite_horario_fim: Horário de término do turno da noite
            $table->foreignIdFor(Company::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_operating_hours');
    }
};
