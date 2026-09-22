<?php

namespace App\Services;

use App\Classes\OwnerManager;
use App\Models\Company;

final class CompanyService
{
    public function createByDeveloper(array $data, array $owners_id): Company
    {
        $company = Company::create([
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'name' => $data['name'],
            'corporate_name' => $data['corporate_name'],
            'tag_url' => $data['tag_url'],
        ]);
        (new OwnerManager())->associateCompany($owners_id,$company->id);
        return $company;
    }

    public function updateByDeveloper(int $id, array $datas)
    {
       
    }

    //  $company = Company::update([
    //         //developer
    //         'cpf' => $data['cpf'],
    //         'cnpj' => $data['cnpj'],
    //         'name' => $data['name'],
    //         'corporate_name' => $data['corporate_name'],
    //         'tag_url' => $data['tag_url'],

    //         //owner
    //         'latitude' => $data['latitude'],
    //         'logintude' => $data['logintude'],
    //         'street' => $data['street'],
    //         'neighborhood' => $data['neighborhood'],
    //         'complement' => $data['complement'],
    //         'number' => $data['number'],
    //         'city' => $data['city'],
    //         'state' => $data['state'],
    //         'cep' => $data['cep'],
    //         'instagram' => $data['instagram'],
    //         'whatsapp' => $data['whatsapp'],
    //         'telegram' => $data['telegram'],
    //         'logo' => $data['logo'],
    //         'pix_key' => $data['pix_key'],
    //         'pix_name' => $data['pix_name'],
    //         //taxa agendamento
    //         'is_have_appointment_tax' => $data['is_have_appointment_tax'],
    //         'is_have_appointments_fee' => $data['is_have_appointments_fee'],
    //         'appointment_tax_value' => $data['appointment_tax_value'],
    //         'is_percentage_appointment_tax_value' => $data['is_percentage_appointment_tax_value'],
    //         'is_fee_per_service' => $data['is_fee_per_service'],
    //         'is_discount_fee' => $data['is_discount_fee'], //desconto caso taxa aplicada
    //         //agendamento futuro
    //         'appointment_max_days_in_advance' => $data['appointment_max_days_in_advance'],
    //         //configs
    //         'is_allowed_reviews' => $data['is_allowed_reviews'], //avaliações
    //         'is_display_employees' => $data['is_display_employees'],
    //         //cancelamento
    //         'is_allowed_cancellation' => $data['is_allowed_cancellation'],
    //         'cancellation_time_allowed' => $data['cancellation_time_allowed'],
    //         'cancellation_time_unit' => $data['cancellation_time_unit'],
    //         //pontos pontuação e promoção
    //         'score_target' => $data['score_target'], //meta
    //         'score_appointments' => $data['cancellation_time_unit'],
    //         'score_product' => $data['score_product'],
    //         'score_service' => $data['score_service'],
    //         'score_is_activeted' => $data['score_is_activeted'],

    //     ]);
}
