<?php
session_start();

class Session{

    private $User = null;

    function __construct($user)
    {
        $this->User = $user;
    }

    public function CheckSession()
    {
        if (isset($_SESSION["userid"]) && isset($_SESSION["token"]))
        {
            if ($_SESSION["userid"] == $this->User->Get("id") && $_SESSION["token"] == $this->User->Get("token"))
            {
                return true;
            }
        }
        
        return false;
    }

    public function GenerateToken($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function CreateSession()
    {
        $token = $this->GenerateToken(20);

        $this->User->Change("token", $token);
        $this->User->Update();

        $_SESSION["userid"] = $this->User->Get("id");
        $_SESSION["token"] = $this->User->Get("token");

        return $token;
    }

    public function DeleteSession()
    {
        $_SESSION["userid"] = "";
        $_SESSION["token"] = "";

        unset($_SESSION);
        session_destroy();
    }
}

?>