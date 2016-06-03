<?php

namespace Routing;

use \Exception as Exception;

require_once('Url.php');

class Facade
{
    public static $url;

    /**
     * Returns the Url instance if it's already created, instantiates it if not
     * @return Url object of type Url
     */
    public static function getUrlInstance()
    {
        if (null === static::$url) {
            static::$url = new Url();
        }
        return static::$url;
    }

    /**
     * magic function that will catch all statically called functions and pass to Url
     * @param  string $name      Name of the function
     * @param  array $arguments Function arguments
     * @return [varies]   Error message or the respective function response
     */
    public static function __callStatic($name, $arguments)
    {
        $url = self::getUrlInstance();
        try {
            $method = self::binder($name);
            return call_user_func_array(array($url, $method), $arguments);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Function that binds the facade params with the real Url functions
     * @param  string $name Name of the called function 
     * @return string       Name of real function in Url
     */
    public static function binder($name)
    {
        $binder = [
                    'set' => 'setRoute',
                    'generate' => 'generateRoutes',
                    'url' => 'getUrl',
                    'locales' => 'setLocale'
                  ];
        if (!isset($binder[$name])) {
            throw new Exception("Error Processing Request", 1);
        }
        return $binder[$name];
    }
}
