<?php 
require "includes/init.php";

$token = bin2hex(random_bytes(32));
$token_hash = hash('sha256',$token);


$expires = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
$expires->modify('+10 minutes ');


$expiresAt = $expires->format('Y-m-d H:i:s');

$db = Database::getConn();
function findUserByEmail($conn, $email){
        $sql = "SELECT id FROM users WHERE email = ?";

         $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // $result = $stmt->get_result();
            return $stmt->get_result()->fetch_assoc();
            // if($result->num_rows === 1){
            //     $user = $result->fetch_assoc();
            //     return [
            //         'userID' => $user['id']
            //     ];
            // }
            // return false;

    }

$success = findUserByEmail($db, 'nisaar@gmail.com');
// var_dump($token);


?>
<h2><?= var_dump(empty(null))?></h2>


