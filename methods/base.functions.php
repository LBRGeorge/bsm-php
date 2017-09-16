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

class BaseFunctions {

    /** @var array store all results for requests */
    private $result = array();

    /**
    * Set result for requests made
    * @param string $key
    * @param string $value
    * @return void
    */
    function SetResult($key, $value)
    {
        $this->result[$key] = $value;
    }

    /**
    * Get result for requests made
    * @return array
    */
    function GetResult()
    {
        return $this->result;
    }

    /**
    * Get data posted on requests
    * @return object
    */
    function getInput()
    {
        $array = array();

        if (sizeof($_POST) > 0)
        {
            foreach($_POST as $key => $value)
            {
                $array[$key] = $value;
            }
        }
        else {
            //Somehow when some post data doesn't goes to global variable $_POST, they get locked at the php://input stream
            //So, to reach them, we read it with file_get_contents
            $raw = file_get_contents('php://input');
            $request = json_decode($raw);

            if (isset($request))
            {
                foreach($request as $key => $value)
                {
                    $array[$key] = $value;
                }
            }
        }

        return json_decode(json_encode($array), FALSE);
    }

    /**
    * Validate user session
    * @return bool
    */
    function ValidateSession()
    {
        //Validate by user session
        if(isset($_SESSION["userid"]) && isset($_SESSION["token"]))
        {
            $user = new User();
            $user->Load($_SESSION["userid"]);

            $session = new Session($user);

            if ($session->CheckSession())
            {
                return true;
            }
        }

        //Validate by user post auth
        if (isset($this->getInput()->user_id) && isset($this->getInput()->user_token))
        {
            $user = new User();
            if($user->Load($this->getInput()->user_id))
            {
                if ($user->Get(TOKEN_COLUMN) == $this->getInput()->user_token)
                {
                    return true;
                }
            }
        }

        return false;
    }
}

?>