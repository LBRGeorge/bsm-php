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

/**
* Class to handle as requested related to user
*/
class UserFunctions extends BaseFunctions {

    private $database = false;
    private $input;

    function __construct()
    {
        $this->database = new Database();
        $this->input = $this->getInput();
    }


    /**
    * Process 'register user' request
    */
    function Register()
    {
        if (isset($this->input->username) && isset($this->input->username))
        {
            $user = new User();
            $id = $user->Register(array(
                "Username"      => $this->input->username,
                "Password"      => hash("SHA512", $this->input->password)
            ));

            if (is_int($id))
            {
                $this->SetResult("ID", $id);
            }
            else $this->SetResult("Error", "Error on registering user.");
        }
        else $this->SetResult("Error", "Invalid parameters!");
    }

    /**
    * Process 'user login' request
    */
    function Login()
    {
        if (isset($this->input->username) && isset($this->input->password))
        {
            $sql = "SELECT * FROM user WHERE Username LIKE '".$this->input->username."'";

            $query = $this->database->Query($sql);

            if ($query["Result"] != "")
            {
                $user = new User($query["Result"]);

                if (strtolower($user->Get("password")) == strtolower(hash("SHA512", $this->input->password)))
                {
                    $session = new Session($user);
                    $token = $session->CreateSession();

                    $this->SetResult("Token", $token);
                }
                else $this->SetResult("Error", "Invalid password!");
            }
            else $this->SetResult("Error", "Username not found!");
        }
        else $this->SetResult("Error", "Invalid parameters");
    }
}