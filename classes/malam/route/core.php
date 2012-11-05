<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Route_Core
{
    protected $routes;

    /**
     * @return Malam_Route
     */
    public static function factory()
    {
        return new self();
    }

    private function __construct()
    {
        $this->routes = Kohana::$config->load('route');
    }

    public function run()
    {
        $default_values = array(
            'is_cli'    => FALSE,
            'regex'     => NULL,
            'defaults'  => NULL,
            'env'       => NULL,
        );

        foreach ($this->routes as $key => $values)
        {
            $values += $default_values;
            extract($values);

            if (((Kohana::$is_cli && TRUE == $is_cli) || (! Kohana::$is_cli && FALSE == $is_cli))
                 AND
                (NULL === $env || $env === Kohana::$environment))
            {
                    Route::set($key, $uri_callback, $regex)->defaults($defaults);
            }
        }
    }
}