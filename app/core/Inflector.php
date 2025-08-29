<?php

namespace Core;

defined('ROOTPATH') or exit('access not allowed');

class Inflector
{
    protected static $irregular = null;
    protected static $uncountable =  null;

    protected static function loadIrregular()
    {
        if (self::$irregular === null) {
            $json = file_get_contents(__DIR__ . '/irregular.json');
            self::$irregular = json_decode($json, true);
        }
        if (self::$uncountable === null) {
            $json = file_get_contents(__DIR__ . '/uncountable.json');
            self::$uncountable = json_decode($json, true);
        }
    }

    public static function pluralize($word)
    {
        self::loadIrregular();
        $lower = strtolower($word);

        // uncountable
        if (in_array($lower, self::$uncountable)) {
            return $word;
        }

        // irregular
        if (array_key_exists($lower, self::$irregular)) {
            return ctype_upper($word[0])
                ? ucfirst(self::$irregular[$lower])
                : self::$irregular[$lower];
        }

        // rules
        if (preg_match('/(s|ss|x|z|ch|sh)$/i', $word)) {
            return $word . 'es';
        }
        if (preg_match('/([bcdfghjklmnpqrstvwxyz])y$/i', $word)) {
            return substr($word, 0, -1) . 'ies';
        }
        if (preg_match('/([aeiou])y$/i', $word)) {
            return $word . 's';
        }
        if (preg_match('/f$/i', $word)) {
            return substr($word, 0, -1) . 'ves';
        }
        if (preg_match('/fe$/i', $word)) {
            return substr($word, 0, -2) . 'ves';
        }
        if (preg_match('/([aeiou])o$/i', $word)) {
            return $word . 's';
        }
        if (preg_match('/([bcdfghjklmnpqrstvwxyz])o$/i', $word)) {
            return $word . 'es';
        }

        return $word . 's';
    }

    public static function singularize($word)
    {
        self::loadIrregular();
        $lower = strtolower($word);

        // uncountable
        if (in_array($lower, self::$uncountable)) {
            return $word;
        }

        // irregular (عكسية)
        $reverse = array_flip(self::$irregular);
        if (array_key_exists($lower, $reverse)) {
            return ctype_upper($word[0])
                ? ucfirst($reverse[$lower])
                : $reverse[$lower];
        }

        // rules
        if (preg_match('/(ches|shes|sses|xes|zes)$/i', $word)) {
            return substr($word, 0, -2);
        }
        if (preg_match('/ies$/i', $word)) {
            return substr($word, 0, -3) . 'y';
        }
        if (preg_match('/ves$/i', $word)) {
            return substr($word, 0, -3) . 'f';
        }
        if (preg_match('/s$/i', $word) && !preg_match('/ss$/i', $word)) {
            return substr($word, 0, -1);
        }

        return $word;
    }

    public static function isPlural($word)
    {
        return self::singularize($word) !== $word;
    }
}
