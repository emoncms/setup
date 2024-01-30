<?php
// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');
function setup_controller()
{
    global $path,$session,$route,$mysqli,$fullwidth;

    $result = false;

    // Special setup access to WIFI function scan and setconfig
    $setup_access = false;
    if (isset($_SESSION['setup_access']) && $_SESSION['setup_access']) $setup_access = true;
    
    require_once "Modules/setup/setup_model.php";
    $setup = new Setup($mysqli);
    
    if ($route->action=="set_status" && $setup_access) {
        $route->format = "text"; 
        $result = $setup->set_status(get("mode"));
    }

    else if ($route->action=="" && $setup_access) {
        if (file_exists("Modules/network/network_view.php")) {
            $result = view("Modules/network/network_view.php",array("mode"=>"setup"));
        } else {
            return ''; // empty strings force user back to login
        }
    }
    
    $fullwidth = false;
    return array('content'=>$result, 'fullwidth'=>true);
}
