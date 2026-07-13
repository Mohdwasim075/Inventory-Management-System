
<?php

// class Database {
//     private $host = "localhost";
//     private $user = "admin@ims";
//     private $password = "Mw[PWDmPb@jWQf*-";
//     private $dbname = "ims";

//     public $conn;

//     public function connect() {
//         $this->conn = new mysqli(
//             $this->host,
//             $this->user,
//             $this->password,
//             $this->dbname
//         );

//         if ($this->conn->connect_error) {
//             die("Connection Failed: " . $this->conn->connect_error);
//         }

//         return $this->conn;
//     }
// }
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
