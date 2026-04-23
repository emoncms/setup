<?php
// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');
function setup_controller()
{
    global $path,$session,$route,$mysqli,$fullwidth;

    $result = false;

    require_once "Modules/setup/setup_model.php";
    $setup = new Setup($mysqli);

    $setup_access = false;
    if ($setup->status()=="unconfigured") {
        $setup_access = true;
    }
    
    if ($route->action=="set_status" && $setup_access) {
        $route->format = "text"; 
        $result = $setup->set_status(get("mode"));
    }

    if ($session["write"] || $setup_access || $route->is_ap) {
    
        $setup_write = false;
        if ($session["write"] || $setup_access) {
            $setup_write = true;
        }
    
        if ($route->action=="") {
            if (file_exists("Modules/network/network_view.php")) {
                $result = view("Modules/network/network_view.php",array("mode"=>"setup", "write"=>$setup_write));
            } else {
                return ''; // empty strings force user back to login
            }
        }
    }
    
    $fullwidth = false;
    return array('content'=>$result, 'fullwidth'=>true);
}
