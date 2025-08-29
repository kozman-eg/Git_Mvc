<?php


namespace Models;

defined('ROOTPATH') or exit('access allwoed');

class Session
{
    public $mainkey = 'APP';
    public $userkey = 'User';

    private function start_session()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return 1;
    }

    public function set(string $keyOrarray, mixed $value = ''): int
    {
        $this->start_session();

        if (is_array($keyOrarray)) {
            foreach ($keyOrarray as $key) {
                $_SESSION[$this->mainkey][$key] = $value;
            }
            return 1;
        }
        $_SESSION[$this->mainkey][$keyOrarray] = $value;
        return 1;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->start_session();

        if (isset($_SESSION[$this->mainkey][$key])) {
            return $_SESSION[$this->mainkey][$key];
            return 1;
        }
        return $default;
    }

    public function authا(mixed $user_row): int
    {
        $this->start_session();
        $_SESSION[$this->userkey][$user_row];
        return 0;
    }
    public function auth(mixed $user_row): int
    {
        $this->start_session();

        // تحويل الـ object (stdClass) إلى array
        $user_array = (array) $user_row;

        // تخزين البيانات كلها تحت مفتاح السيشن الخاص بالمستخدم
        $_SESSION[$this->userkey] = $user_array;

        return 0;
    }

    public function loguot(): int
    {
        $this->start_session();
        if (!empty($_SESSION[$this->userkey])) {
            unset($_SESSION[$this->userkey]);
        }
        return 0;
    }
    public function is_logged_in(): bool
    {
        $this->start_session();
        if (!empty($_SESSION[$this->userkey])) {
            return true;
        }
        return false;
    }
    /*public function is_admin(): bool
    {
        $this->start_session();
        $db = new \Core\Database();
        
        if (!empty($_SESSION[$this->userkey])){
            $arr = [];
            $arr ['id'] = $_SESSION[$this->userkey]->role_id;
            if($db->get_row("SELECT * FROM auth_role WHERE id = :id && role = 'admin' LIMIT 1",$arr)){

                return true;
            }
        }
        return false;
    }*/
    public function user(string $key, mixed $default = null): mixed
    {
        $this->start_session();

        if (empty($key) && !empty($_SESSION[$this->userkey])) {
            return $_SESSION[$this->userkey];
        }
        if (!empty($_SESSION[$this->userkey]->$key)) {
            return $_SESSION[$this->userkey]->$key;
        }
        return $default;
    }
    public function remove(string $key): void
    {
        if (isset($_SESSION[$this->mainkey][$key])) {
            unset($_SESSION[$this->mainkey][$key]);
        }
    }
    public function pop(string $key, mixed $default = ''): mixed
    {
        $this->start_session();

        if (!empty($_SESSION[$this->mainkey][$key])) {

            $value = $this->get($key);
            $this->remove($key);
            return $value;
        }
        return $default;
    }
    public function all(): mixed
    {
        $this->start_session();

        if (!empty($_SESSION[$this->mainkey])) {
            return $_SESSION[$this->mainkey];
        }
        return [];
    }
    public function has(string $key): bool
    {
        $this->start_session();

        return isset($_SESSION[$key]);
    }



    public function destroy(): void
    {
        $_SESSION = [];
        if (session_id() !== '') {
            session_destroy();
        }
    }
}
