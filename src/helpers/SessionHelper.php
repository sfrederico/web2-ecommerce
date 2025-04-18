<?php
class SessionHelper {
    public static function isSessionStarted() {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}