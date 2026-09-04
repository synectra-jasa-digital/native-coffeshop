<?php
namespace App\Core;

class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function setFlash($key, $message) {
        self::set('flash_' . $key, $message);
    }

    public static function getFlash($key) {
        $flashKey = 'flash_' . $key;
        if (self::has($flashKey)) {
            $message = self::get($flashKey);
            self::remove($flashKey);
            return $message;
        }
        return null;
    }
    
    public static function hasFlash($key) {
        return self::has('flash_' . $key);
    }

    public static function destroy() {
        session_unset();
        session_destroy();
    }
}
