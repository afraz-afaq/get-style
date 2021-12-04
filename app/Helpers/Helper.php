<?php

namespace App\Helpers;

class Helper
{

    public static function uploadFile($file, $name)
    {
        $file->storeAs('profile-images', $name, 'public');
    }

    public static function keyValueExists($array, $value)
    {
        return isset($array[$value]) && $array[$value] != "";
    }
}
