<?php

namespace Routing;

class Url
{
    private $routes=[];
    private $bindings=[];
    private $locales=[];

    /**
     * Sets the available locales
     * @param array $locales array with locale strings
     */
    public function setLocale($locales)
    {
        $this->locales = $locales;
    }

    /**
     * Sets a new route based on the gven parameters 
     * @param string  $key   The url segment
     * @param string  $value The function that segment is mapped to
     * @param string $alias Alias for using the route throughout the system
     */
    public function setRoute($key, $value, $alias = false)
    {
        if (!$alias) {
            $alias = $key;
        }
        $this->bindings[$alias] = ['key' => $key, 'value' => $value];
    }

    /**
     * Function that generates all CI routes based on the added items
     * @return array Array of all routes
     */
    public function generateRoutes()
    {
        foreach ($this->bindings as $key => $value) {
            $this->routes[$value['en']['key']] = $value['en']['value'];
            $locales = $this->locales;
            foreach ($this->locales as $locale) {
                if(isset($value[$locale]['value'])) {
                    $this->routes[$locale.'/'.$value[$locale]['key']] = $value[$locale]['value'];
                    unset($locales[array_search($locale, $locales)]);
                }
            }
            if($locales) {
                if($value['en']['key'] == 'default_controller') {
                    $value['en']['key'] = '';
                }
                $params = preg_match_all('/\$([1-9]+)/', $value['en']['value'], $matches);
                $old_patterns = $new_patterns = $matches[1];
                array_walk($old_patterns, function(&$item, $key) {
                    $item = "/{$item}/";
                });
                array_walk($new_patterns, function(&$item, $key) {
                    $item = (string) ($item+1);
                });
                krsort($old_patterns);
                krsort($new_patterns);

                $locale_key = rtrim($this->getLocalePattern().'/'.$value['en']['key'],'/');
                $this->routes[$locale_key] = preg_replace($old_patterns, $new_patterns, $value['en']['value']);
            }
        }
        return $this->routes;
    }

    /**
     * Returns the urls based on the alias
     * @param  string $pattern The alias used for returing the url
     * @return           [description]
     */
    public function getUrl($pattern, $lang = 'en')
    {
        if(array_key_exists($pattern, $this->bindings)) {
            $url = isset($this->bindings[$pattern][$lang]) ? $this->bindings[$pattern][$lang]['key'] : $this->bindings[$pattern]['en']['key'];
            $param_pos = strpos($url, '/(');
            if($param_pos) {
                return substr($url, 0, $param_pos);
            }
            return $url;
        } 
        return '';  
    }

    /**
     * Returns the route pattern from the locales array
     * @return string Route pattern
     */
    public function getLocalePattern()
    {
        if (!$this->locales) {
            return '';
        }
        return '('.implode('|', $this->locales).')';
    }
}
