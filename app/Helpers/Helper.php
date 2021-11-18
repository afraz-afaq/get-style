<?php

namespace App\Helpers;

class Helper
{

    public static function uploadFile($file, $name)
    {
        $file->storeAs('profile-images', $name,'public');
    }

}
