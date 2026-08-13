<?php

use App\Enum\AppointmentsStatus;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Service;
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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('reason_canceled')->nullable();
            $table->datetime('time_scheduled');
            $table->decimal('discount_value', 5,2)->nullable();
            $table->boolean('is_discount_percentage')->nullable();
            $table->smallInteger('rate')->nullable();
            $table->enum('status', AppointmentsStatus::toArrayValues())->default(AppointmentsStatus::WAITING->value); #numero do cliente no dia do corte
            $table->decimal('amount_fee', 6,2)->nullable();
            $table->string('guest_name')->nullable();
            $table->string('guest_whatsapp')->nullable();
            $table->foreignIdFor(Company::class)->constrained();
            $table->foreignIdFor(Employee::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Service::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
