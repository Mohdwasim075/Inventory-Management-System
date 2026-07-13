<?php

;
/**
 * Authentication
 * 
 * Login and Logout
 */

class Auth{
    /**
     * Check whether the user is still logged in
     * 
     */

    public static function isLoggedIn(){
          return isset($_SESSION['user']) ;
    }

   public static function requireRole($role)
{
    $user = self::user();

    if ($user === null) {
        Url::redirect('/login.php');
        exit;
    }

    if ($user['role'] !== $role) {
        Url::redirect('/unauthorized.php');
        exit;
    }
}


    /**
     * 
     * The user should be Loggedin or else stop , display unauthorised message
     * 
     */
    public static function requireLogin(){
        if(! static::isLoggedIn()){
            die('Unauthorised');

        }

    }

   /**
    * Login User and Set the Session
    */

    public static function Login(){


        // $_SESSION['is_logged_in'] = true;
        if(isset($_SESSION['user'])){
            session_regenerate_id(true);
        }
        
    }
     public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function companyId(): ?int
    {
        return $_SESSION['user']['company_id'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    public static function name(): string
    {
        return $_SESSION['user']['name'] ?? null;
    }

    

    /**
     * Log out using the session
     *
     * @return void
     */
    public static function Logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}