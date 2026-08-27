<?php
if (!function_exists('convertToMoney')) {

    /**
     * convertToMoney
     *
     * @param  string $money
     * @return float
     */
    function convertToMoney(string $money): float
    {
        return (float) str_replace(['.', ','], ['', '.'], $money);
    }
}
if (!function_exists('getMoneyToStringBr')) {


    /**
     * getMoneyToStringBr
     *
     * @param  float $money
     * @return string
     */
    function getMoneyToStringBr(float $money): string
    {
        return number_format($money, 2, ',', '.');
    }
}