<?php

use App\Enum\TimeUnit;
use App\Models\CompanyOperatingHours;
use App\Models\CompanySystemSetting;
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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('cnpj', 18)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('corporate_name')->nullable();
            $table->string('name');
            #endereço
            $table->decimal('latitude', 10, 8)->nullable(); #valor max & min 90.00000000 (ou mínimo -90.00000000)
            $table->decimal('longitude', 11, 8)->nullable(); #valor max & min 180.00000000 (ou mínimo -180.00000000)
            $table->string('street'); #rua
            $table->string('neighborhood'); #bairro
            $table->string('complement')->nullable(); #complemento
            $table->string('number', 20); #numero estabelecimento
            #dados sociais
            $table->string('instagram')->nullable(); #link url
            $table->string('whatsapp')->nullable(); #link url
            $table->string('telegram')->nullable(); #link url
            $table->string('tag_url')->unique();
            $table->string('logo')->nullable();
            #pix e taxa
            $table->string('pix_key', 100);
            $table->string('pix_name');
            $table->boolean('is_have_appointment_tax')->default(false);
            $table->double('appointment_tax_value', 5,2)->nullable();
            $table->boolean('is_percentage_appointment_tax_value')->nullable(); #porcentagem do serviço agendado
            #agendamento regras
            $table->time('appointment_allowed_delay_time'); #tempo permitido de atraso para chegar, fazer regra para máximo tempo
            $table->boolean('is_allowed_reviews')->default(false); #permitido avaliação
            $table->boolean('is_display_employees')->default(false); #permitido exibir funcionarios
            $table->boolean('is_allowed_cancellation')->default(false); #permitido cancelamento
            $table->smallInteger('cancellation_time_allowed')->nullable();
            $table->enum('cancellation_time_unit', TimeUnit::toArrayValues())->nullable();
            $table->tinyInteger('appointment_max_days_in_advance')->nullable(); #dias máximos futuros para agendar, nulo apenas no dia
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
