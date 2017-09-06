<?php
/**
 * Basic Simple Module
 * ------------------------------------
 * config.php
 *
 * API configuration file
 * 
 * @author George Carvalho
 */

session_start();

class Session{

    private $User = null;

    /**
	* Constructor to start session
	* @param object $user instance of the user
	*/
    function __construct($user)
    {
        $this->User = $user;
    }

    /**
	* Check if user session exist and is valid
	* @return bool
	*/
    public function CheckSession()
    {
        if (isset($_SESSION["userid"]) && isset($_SESSION["token"]))
        {
            if ($this->User->GetPrimary() != null)
            {
                if ($_SESSION["userid"] == $this->User->GetPrimary() && $_SESSION["token"] == $this->User->Get("token"))
                {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
	* Generate random token
    * @param int $length size of the string, not required
	* @return string
	*/
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

    /**
	* Create new session for user
	* @return string
	*/
    public function CreateSession()
    {
        $token = $this->GenerateToken(256);

        $this->User->Change("token", $token);
        $this->User->Update();

        $_SESSION["userid"] = $this->User->GetPrimary();
        $_SESSION["token"] = $this->User->Get("token");

        return $token;
    }

    /**
	* Destroy user session
	* @return void
	*/
    public function DeleteSession()
    {
        $_SESSION["userid"] = "";
        $_SESSION["token"] = "";

        unset($_SESSION);
        session_destroy();
    }
}

?>