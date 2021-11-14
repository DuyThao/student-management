<?php


namespace App\Config;

class Config {
    public static function get($path = null){
        if($path){
            $paths = explode('/',$path);
            $config = $GLOBALS['config'];

            foreach($paths as $bit){
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config;
        }else {
            return new \Exception('Configuration path not set');
        }
    }
}