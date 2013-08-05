<?php
namespace Esperluette\Model;

class Notification
{
    public static $types = array(
        'success',
        'error'
    );

    public static function read()
    {
        // Get notification
        
        // Build html
        
        // Erase (consume)
        
    }

    public static function write($type, $message)
    {
        // Add notification to pool if type exists
        if (in_array($type, static::$types)) {

        }
    }
}