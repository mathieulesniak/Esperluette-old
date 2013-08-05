<?php
namespace Fwk;

class Registry
{
    protected static $data = array();
    protected static $context;

    public static function get($key, $defaultValue = null)
    {
        $key = static::addContext($key);

        if (isset(static::$data[$key])) {
            return static::$data[$key];
        }
        
        return $defaultValue;
    }

    public static function getProperty($key, $property, $defaultValue = null)
    {
        $key = static::addContext($key);
        if ($object = static::get($key)) {
            if (isset($object->$property)) {
                return $object->$property;
            }
        }

        return $defaultValue;
    }

    public static function set($key, $value)
    {
        $key = static::addContext($key);
        static::$data[$key] = $value;
    }

    public static function exists($key)
    {
        $key = static::addContext($key);
        return isset(static::$data[$key]);
    }

    private static function addContext($key)
    {
        if (static::$context != '') {
            return static::$context . '.' . $key;
        } else {
            return $key;
        }
    }
}
