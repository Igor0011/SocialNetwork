<?php

class Helper
{
    // Method to convert a string to title case
    public static function toTitleCase($string)
    {
        return ucwords(strtolower($string));
    }

    // Method to truncate a string to a specified length
    public static function truncate($string, $length = 100, $suffix = '...')
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length) . $suffix;
        }
        return $string;
    }

    // Method to check if a string contains another string
    public static function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

//ex call
//echo StringHelper::toTitleCase($string);

?>