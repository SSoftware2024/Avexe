<?php

namespace App\Services;

use App\Enum\TypeUser;
use App\Models\Company;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\CompanyOperatingHoursService;
use App\Services\PaymentMethodService;
use Illuminate\Database\Eloquent\Collection;

final class CompanyService
{
    public function create(array $data, array $owners_id): Company
    {
        $company = Company::create([
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'name' => $data['name'],
            'corporate_name' => $data['corporate_name'],
            'tag_url' => $data['tag_url'],
        ]);
        $this->associateOwners($owners_id, $company->id);
        $this->associatePaymentMethods($company->id);

        return $company;
    }

    public function updateByDeveloper(int $id, array $data, array $old_owners_id): int
    {

        $total_rows = Company::where('id', $id)->update([
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'name' => $data['name'],
            'corporate_name' => $data['corporate_name'],
            'tag_url' => $data['tag_url'],
        ]);
        $owners_id = $data['owners_ids'];
        $new_owners_id = array_values(array_diff($owners_id, $old_owners_id));
        $remove_owners_id = array_values(array_diff($old_owners_id, $owners_id));
        if (!empty($remove_owners_id)) {
            $total_rows += $this->removeOwners($id, $remove_owners_id);
        }
        if (!empty($new_owners_id)) {
            $total_rows += $this->associateOwners($new_owners_id, $id);
        }
        return $total_rows;
    }

    public function getOwnersNotSelected(): Collection
    {
        return User::where('user_type', TypeUser::OWNER->value)
            ->whereNull('company_id')->get(['id', 'name', 'user_type']);
    }
    public function getAllOwnersAvailable(?array $ids = []): Collection
    {
        $user = User::where('user_type', TypeUser::OWNER->value)
            ->whereNull('company_id');
        if (!empty($ids)) { //caso de edição, mostra os relacionados
            $user->orWhereIn('id', $ids);
        }
        return $user->get(['id', 'name', 'user_type']);
    }
    public function getOwnersSelectedByCompany(int $id): Collection
    {
        return User::where('user_type', TypeUser::OWNER->value)
            ->where('company_id', $id)->get(['id', 'name', 'user_type']);
    }

    public function toggleActive(int $id)
    {
        $company = Company::select('id', 'active')->find($id);
        $company->active = !$company->active;
        $company->save();
    }

    public function delete(int $id)
    {
        $owners_disassociated_total = $this->removeOwners($id);
        $this->removePaymentMethods($id);
        (new CompanyOperatingHoursService($id))->delete();
        (new CompanySystemSettingsService($id))->delete();
        Company::where('id', $id)->forceDelete();
        return $owners_disassociated_total;
    }

    # ================================================================================= #
    #                                    View
    # ================================================================================= #
    public function listViewData(?int $paginate = 10, array $sort_by = [])
    {
        $company = Company::query();
        if (!empty($sort_by)) { //vuetify datatable
            $company->orderBy($sort_by[0]['key'], $sort_by[1]['order']);
        }
        $company = $company->paginate($paginate);
        return $company;
    }
    public function createOrUpdateViewData(?int $id = null): array
    {

        $ownersByCompany = [];
        $company = $owners_avalable = $operatingHours = $systemSettings = null;
        if (!empty($id)) {
            $ownersByCompany = $this->getOwnersSelectedByCompany($id);
            $company = Company::select('id', 'name', 'corporate_name', 'cpf', 'cnpj', 'tag_url', 'active')->find($id);
            $owners_avalable = $ownersByCompany->pluck('id')->toArray();
            $operatingHours = (new CompanyOperatingHoursService($id))->get();
            $systemSettings = (new CompanySystemSettingsService($id))->get();
            empty($systemSettings) ? null : $systemSettings->value_monthly_fee_formated = getMoneyToStringBr($systemSettings->value_monthly_fee);
        }
        $ownersAvalable = $this->getAllOwnersAvailable($owners_avalable);

        return compact('ownersAvalable', 'ownersByCompany', 'operatingHours', 'systemSettings', 'company');
    }

    # ================================================================================= #
    #                                    PRIVADOS
    # ================================================================================= #
    private function associateOwners(array $owners_id, int $id): int
    {
        return User::whereIn('id', $owners_id)->update([
            'company_id' => $id
        ]);
    }
    private function removeOwners(int $id, ?array $owners_id = null): int
    {
        $user = User::where('company_id', $id);
        !empty($owners_id) ? $user->whereIn('id', $owners_id) : null;
        return $user->update([
            'company_id' => null
        ]);
    }
    private function associatePaymentMethods(int $id)
    {
        $paymentMethodsService = new PaymentMethodService()->setCompanyId($id);
        $paymentMethodsJson = $paymentMethodsService->getJsonFilePath(decoded: true);
        if (!empty($paymentMethodsJson) && $paymentMethodsService->isValidJson()) {
            foreach ($paymentMethodsJson as $value) {
                $paymentMethodsService->create((array) $value);
            }
        }
    }
    private function removePaymentMethods(int $id): int
    {
        return PaymentMethod::where('company_id', $id)->forceDelete();
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
