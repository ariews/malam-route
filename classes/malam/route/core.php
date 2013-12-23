<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

class Malam_Route_Core
{
    protected $routes;

    const CLI_ONLY          = 1;
    const NOT_CLI           = 2;
    const CLI_AND_NOT_CLI   = 3;

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
            'mode'      => Malam_Route::NOT_CLI,
            'regex'     => NULL,
            'defaults'  => NULL,
            'env'       => NULL,
        );

        foreach ($this->routes as $key => $values)
        {
            $values += $default_values;
            extract($values);

            if (TRUE === $is_cli)
            {
                $mode = Malam_Route::CLI_ONLY;
            }

            $error = FALSE;

            if ($env !== NULL && $env !== Kohana::$environment)
                $error = TRUE;

            if (TRUE === Kohana::$is_cli && $mode === Malam_Route::NOT_CLI)
                $error = TRUE;

            if (FALSE === Kohana::$is_cli && $mode === Malam_Route::CLI_ONLY)
                $error = TRUE;

            if (FALSE === $error)
            {
                Route::set($key, $uri_callback, $regex)->defaults($defaults);
            }
        }
    }
}
