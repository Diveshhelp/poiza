<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function flatten($array, $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix . (is_numeric($key) ? "[$key]" : ($prefix ? '.' : '') . $key);
            if (is_array($value) && !empty($value)) {
                $result = array_merge($result, self::flatten($value, $newKey));
            } else {
                $result[] = [$newKey, self::formatValue($value)];
            }
        }
        return $result;
    }

    private static function formatValue($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_array($value) && empty($value)) {
            return '[]';
        } else {
            return $value;
        }
    }
}
