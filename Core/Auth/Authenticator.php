<?php

namespace App\Auth;

use App\Database;
use App\Model\User;

class Authenticator
{
    protected $user;
    protected $db;

    public function __construct(Database $db, User $user)
    {
        $this->user = $user;
        $this->db = $db;
    }
    public function attempt()
    {
        $user = $this->db
            ->query('select * from user where name = :username', [
                'username' => $this->user->getUsername()
            ])->getObject(User::class);
        if ($user) {
            if (password_verify($this->user->getPassword(), $user->getPassword())) {
                static::login([
                    'username' => $this->user->getUsername()
                ]);
                return true;
            }
        }

        return false;
    }

    public static function login($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user'] = [
            'username' => $user['username']
        ];

        session_regenerate_id(true);
    }
    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    public static function isLogged()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    public static function checkPermission()
    {
        if (!static::isLogged()) {
            throw new AuthenticationException('Access Forbidden!');
        }
    }
}
