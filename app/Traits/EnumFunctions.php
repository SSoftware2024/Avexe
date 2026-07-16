<?php

namespace App\Traits;

trait EnumFunctions
{
    /**
     * toArray
     *
     * Transforma valores(value) em único array
     * @return array
     */
    public static function toArrayValues(): array
    {
        return array_map(
            fn($value) => $value->name,
            self::cases()
        );
    }

    /**
     * toArrayPortuguese
     *
     * Pega valores do enum e traduz para o português para visualização do cliente,
     * caso não tenha tradução, retorna array vazio
     * @return array
     */
    public static function toArrayPortuguese()
    {
        // $english_portuguese = [
        //     'pay' => 'pagar',
        //     'paid' => 'paga',
        //     'late' => 'atrasada',
        //     'overdue' => 'vencida',
        // ];
        // $array = self::toArrayValues();
        // $array_translated = [];
        // foreach ($array as $value) {
        //     in_array($value, $array, true) ? $array_translated[$value] = $english_portuguese[$value] : null;
        // }
        // return $array_translated;
    }
}