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
$contactNumber = 809002100012

?>

        <?php if(strlen($contactNumber) <= 10) :?>
                <h2>true</h2>
        <?php else: ?>
                <h2>false</h2>
        <?php endif; ?>

<?php
$arr = array('volvo','bmw','hyundai');


// strings in php 

//string built-in functions

//strlen() 
//str_contains()
//str_word_count()
//str_starts_with()
// str_ends_with

$word = "Hello lovely bird!";
$word_arr = explode(" ", $word);
var_dump($word_arr);

//match 

$favColor = "red";

$result = match($favColor){
        "red" => "Your fav color is red",
        "yellow" => "Your fav color is blue",
        default =>"Neither red nor black",
};

var_dump($result);
$fullName = "Mohammed Wasim";
var_dump(strlen($fullName));
var_dump(str_word_count($fullName));


$members = [
        "wasim" => 24,
        "Aneesha" => 19,
        "Suhana" => 6,
        "Siddik"=> 12
];

foreach($members as $name => &$member){
        $member = 'person';

}
unset($member);


//  arsort($members);
var_dump($members);