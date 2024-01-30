<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

class Setup
{
    private $mysqli;
    
    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    public function status() {
    
        $wifi = "unconfigured";
        $result = $this->mysqli->query("SELECT wifi FROM setup");
        if ($result && $row = $result->fetch_object()) {
            $wifi = $row->wifi;
        }
        return $wifi;
    }
    
    public function set_status($val) {
        if ($val=="ethernet" || $val=="standalone" || $val=="client") {
            
            $result = $this->mysqli->query("SELECT wifi FROM setup");
            if ($result->num_rows==0) {
                $this->mysqli->query("INSERT INTO setup (wifi) VALUES ('$val')");
            } else {
                $this->mysqli->query("UPDATE setup SET `wifi`='$val'");
            }
            return "saved:$val";
        } else {
            return "Invalid value";
        }
    }
}
