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

    public static function getRatingFromSentimentOutput($output)
    {
        $compound = $output['compound'];
        if ($compound <= 0.15)
        {
            return 1;
        }
        elseif ($compound > 0.15 && $compound < 0.2)
        {
            return 1.5;
        }
        elseif ($compound >= 0.2 && $compound < 0.3)
        {
            return 2;
        }
        elseif ($compound >= 0.3 && $compound < 0.4)
        {
            return 2.5;
        }
        elseif ($compound >= 0.4 && $compound < 0.45)
        {
            return 3;
        }
        elseif ($compound >= 0.45 && $compound < 0.5)
        {
            return 3.5;
        }
        elseif ($compound >= 0.5 && $compound < 0.65)
        {
            return 4;
        }
        elseif ($compound >= 0.65 && $compound < 0.75)
        {
            return 4;
        }
        elseif ($compound >= 0.75 && $compound < 0.9)
        {
            return 4.5;
        }
        else
        {
            return 5;
        }
    }
}
