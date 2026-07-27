
<?php


Class Database{

    public static function getConn(){
        $db_host = "localhost";
        $db_user = "admin@ims";
        $db_password = "Mw[PWDmPb@jWQf*-";
        $db_name = "ims";

    
        $conn = new mysqli($db_host,$db_user,$db_password,$db_name);
        return $conn;


        }
        
}
