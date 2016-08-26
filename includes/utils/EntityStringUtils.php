<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class EntityStringUtils {

    /**
     *
     */
    public static function is_invalid_string($string){
        return (!isset($string) || trim($string)==='');
    }
    public static function starts_with($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function ends_with($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
    /**
     *  crypto_rand_secure function
     */
    public static function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0)
            return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    /**
     *  Random token generator
     */
    public static function get_token($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure(0, strlen($codeAlphabet))];
        }
        return $token;
    }

    /*------------------------------------------------------------------------------
    SYNOPSIS: a simple parsing function for basic templating.
    INPUT:
        $tpl (str): a string containing [+placeholders+]
        $hash (array): an associative array('key' => 'value');
    OUTPUT
        string; placeholders corresponding to the keys of the hash will be replaced
        with the values and the string will be returned.
    ------------------------------------------------------------------------------*/
    public static function parse($tpl, $hash) {
        foreach ($hash as $key => $value) {
            if($key != 'attachments') {
                $tpl = str_replace('[+'.$key.'+]', $value, $tpl);
            }
        }
        return $tpl;
    }

    
}


?>