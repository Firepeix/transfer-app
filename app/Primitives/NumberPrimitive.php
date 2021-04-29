<?php


namespace App\Primitives;


class NumberPrimitive
{
    public static function toReal(int $amount) : string
    {
        $value = $amount / 100;
        return number_format($value, 2, ',', '.');
    }
    
    public static function toInt($value) : int
    {
        $negative_Integer = strpos(substr($value, 0, 1), '-') !== false;
    
        if ($value !== null) {
            $value = self::clean($value);
        }
        if ($negative_Integer) {
            $value *= -1;
        }
    
        return $value;
    }
    
    public static function clean($value) : string
    {
        if (is_float($value)) {
            $value = number_format($value, 2);
        }
        return preg_replace('/\D/', '', $value);
    }
}
