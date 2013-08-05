<?php

namespace Fwk;

class Helper
{
    public static function dump()
    {
        echo '<pre>';
        call_user_func_array('var_dump', func_get_args());
        echo '</pre>';
    }

    public static function debug($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}