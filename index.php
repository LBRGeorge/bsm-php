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

require "autoload.php";

$output = array("Error" => "");
$result = false;

$function = "";
$action = "";
if (isset($_REQUEST["f"])) $function = $_GET["f"];
if (isset($_REQUEST["a"])) $action = $_GET["a"];

if ($function == "user")
{
    $func = new UserFunctions();
    $result = false;

    switch($action)
    {
        case "register":
            $func->Register();
            $result = $func->GetResult();
            break;

        case "login":
            $func->Login();
            $result = $func->GetResult();
            break;
    }
}


/*-----------------------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------------------------------------------*/


if ($result != false)
{
    foreach($result as $key => $value)
    {
        $output[$key] = $value;
    }
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header("Content-Type: application/json");
echo json_encode($output);
?>