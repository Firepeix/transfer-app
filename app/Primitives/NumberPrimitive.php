<?php


namespace App\Primitives;


class NumberPrimitive
{
    public static function toReal(int $amount) : string
    {
        $value = $amount / 100;
        return number_format($value, 2, ',', '.');
    }
}
