<?php

namespace App\Http\Controllers;

use App\Enum\AccountType;
use App\Facades\DialogAlert;
use App\Http\Controllers\Controller;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;


class PaymentMethodController extends Controller
{
    public function index(PaymentMethodService $service)
    {
        $json_database_base = $service->getJsonFilePath('database', true);
        $json_running = $service->getJsonFilePath('storage', true);
        return Inertia::render('Dev/PaymentMethod', [
            'json_running' => $json_running == false ? 'Arquivo inexistente' : $json_running,
            'json_database_base' => $json_database_base == false ? 'Arquivo inexistente' : $json_database_base
        ]);
    }
    public function createJson(Request $request, PaymentMethodService $service)
    {
        $validator = Validator::make($request->all(), [
            'json_content' => ['required', 'array'],
            'json_content.*' => ['required', 'array:name,account_type'],
            'json_content.*.account_type' => [
                'required',
                'string',
                Rule::in(AccountType::toArrayValues()),
            ],
            'json_content.*.name' => [
                'required',
                'string',
                'regex:/^[A-ZÀ-ÖØ-Þ\s]+$/u'
            ],
        ], [
            'json_content.required' => 'O conteúdo JSON é obrigatório.',
            'json_content.array' => 'O conteúdo JSON deve ser uma lista de objetos.',
            'json_content.*.array' => 'Cada item do JSON deve ser um objeto com os campos account_type e name.',
            'json_content.*.type.in' => 'O campo type deve ser receivable ou payable.',
            'json_content.*.name.string' => 'O campo name deve ser um texto.',
            'json_content.*.name.regex' => 'Campo name só aceita palavras maiúsculas.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            $message = collect($errors)
                ->map(fn($error, $index) => ($index + 1) . '. ' . $error)
                ->implode("<br>");

            DialogAlert::error($message);
        } else {
            if ($service->exists()) {
                if ($request->overwrite == false) {
                    session()->flash('response_data', [
                        'json_overwrite' => true
                    ]);
                } else {
                    $service->createJson(json_encode($request->json_content));
                    DialogAlert::success('Formas de pagamento padrão, salvas com sucesso');
                }
            } else {
                $service->createJson(json_encode($request->json_content));
                DialogAlert::info('Json criado a partir do json database');
            }
        }
    }
    public function create() {}
    public function update() {}
    public function delete() {}
}
