<?php
/**
 * Created by PhpStorm.
 * User: JeffVandenberg
 * Date: 5/16/2015
 * Time: 9:09 PM
 */

namespace JeffVandenberg\Utility;


class Config
{
    private static $config;

    private function __construct()
    {
        self::$config = array();
    }

    public static function write($key, $value)
    {
        self::$config[$key] = $value;
    }

    public static function read($key, $default = null)
    {
        $value = $default;
        if(isset(self::$config[$key])) {
            $value = self::$config[$key];
        }
        return $value;
    }
}