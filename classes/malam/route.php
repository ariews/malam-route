<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Route
{
    protected $routes;

    /**
     * @return Malam_Route
     */
    public static function instance()
    {
        static $instance;
        empty($instance) && $instance = new self;
        return $instance;
    }

    private function __construct()
    {
        $this->routes = Kohana::find_file('config', 'route', NULL, TRUE);
        sort($this->routes);
    }

    public function run()
    {
        if (! $this->routes)
            return;

        $default_values = array(
            'is_cli'    => FALSE,
            'regex'     => NULL,
            'defaults'  => NULL,
        );

        foreach ($this->routes as $route_config)
        {
            $temp = require_once $route_config;

            if ($temp)
            {
                foreach ($temp as $key => $values)
                {
                    $values += $default_values;
                    extract($values);

                    if ((Kohana::$is_cli && TRUE == $is_cli) || (! Kohana::$is_cli && FALSE == $is_cli))
                    {
                        Route::set($key, $uri_callback, $regex)->defaults($defaults);
                    }
                }
            }
        }
    }
}