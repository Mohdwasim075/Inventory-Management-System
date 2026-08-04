<?php 
// var_dump(extension_loaded('mysqli'));
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
// var_dump($word_arr);

//match 

$favColor = "red";

$result = match($favColor){
        "red" => "Your fav color is red",
        "yellow" => "Your fav color is blue",
        default =>"Neither red nor black",
};

// var_dump($result);
// $fullName = "Mohammed Wasim";
// var_dump(strlen($fullName));
// var_dump(str_word_count($fullName));


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


// //  arsort($members);
// var_dump($members);

$string = "Hershey Milk";

function checkTarget($string, $k){
    for($i=0; $i<strlen($string); $i++){
        if($string[$i] == $k){
            return $string[$i];
        }
}
    return false;
}

$word = "abc";

function checkPalindrome($word){
        $len = strlen($word) ;
        $word = strtolower($word);

        for($i=0; $i < floor($len /2 ); $i++){
                echo "{$word[$i]} --> {$word[$len -1 - $i]} <br>";
                if($word[$i] !== $word[$len -1 - $i]){
                        return false;
                }
                
        }
        return true;

}

$array = [10,20,30,40,50];

$reversedArray = [];
for($i= count($array) - 1; $i>= 0; $i--){
        $reversedArray[] = $array[$i];

}
// var_dump($reversedArray);
$palindromeString = "able was I ere I saw Elba";
echo "<br>";
// var_dump(floor(strlen('able was I ere I saw Elba')/ 2));
// var_dump($palindromeString[12]);

//echo checkPalindrome('able was I ere I saw Elba')? 'is palindrome' : "Not a palindrome";

$name = 'Mohammed wasim';
function reverseString($word){
        $revString = "";
        for($i= 0; $i< strlen($word); $i++){
                $revString .= $word[strlen($word) -1 - $i]; 
        }
        return $revString;

}
// var_dump(reverseString('apple'));
class Fruit {

        protected $name;
        public $color;

        // function __construct($name, $color){
        //         $this->name = $name;
        //         $this->color = $color;
        // }

        // function set_details($name, $color){
        //         $this->name = $name;
        //         $this->color = $color;

                
        // }
        function setType($name){
                $this->name = $name;
        }

        function get_details(){
                echo "Name: " . $this->name . ". Color: " . $this->color . ".<br>";
        }
}

class Apple extends Fruit{
        public function getType(){
                echo "Name: " . $this->name . ".";
        }
}

// $apple = new Fruit("Mangrine", "orange");

// // $apple->get_details();
// $apple = new Apple();
// $apple->setType("Apple");
// echo $apple->getType();
// var_dump($apple instanceof Fruit);

$a = "a,b,c,d,e,f";

$a = explode(",", $a);
// var_dump( $a);

$sentence = "PHP is easy to learn";

$arr_sentence = explode(" ", $sentence);
//var_dump($arr_sentence);


$new_array = array();
// foreach($a as $element){
//         $new_array[$element] = $element;

// }

// print_r($new_array);

$fruits = ['apple', 'kiwi', 'orange'];

$fruits = implode(',', $fruits);
//print_r("$fruits")


// print number from 1 to 10

// for($i=0; $i < 10 + 1; $i++){
//         echo $i . "<br>";

// }

//print even numbers from 1 to 10

// for($i=0; $i < 10 + 1; $i++){
//         if($i % 2 == 0){
//                 echo $i . "<br>";

//         }

// }

//count no of digits

$num = 87657;
// echo $string . "<br>";
// echo substr($string,6 ,-3);
// str_replace();
// strrev();

function reverseNumber($num){
        $revNum = 0;

        while ($num > 0 ){
                $digit = $num %  10;
                $revNum = ($revNum * 10) + $digit;
                $num = intdiv($num , 10);
        }

        echo $revNum;

}

// reverseNumber(12345);

function searchNum($num, $digit){

        while($num > 0){
                if(($num % 10) === $digit){
                        echo "found : $digit";
                        return ;

                }
                $num = intdiv($num , 10);

        }
        echo "Not found; $digit";
        return ;

}

// searchNum(1234, 2);
// echo sqrt(29);
$numArr = [10, 56, 12, 34];
function findmax($array){
        $maxVal = 0;
       
        foreach($array as $element){
                if($element > $maxVal){
                        $maxVal = $element;
                }

        }
        return $maxVal;

}

// var_dump(findmax([]));

function removeDuplicates($arr){

        $result = [];

        foreach($arr as $element){
                if(! in_array($element, $result)){
                        $result[] = $element;
                }
        }

        return $result;
}
$arr = [1,2,2,3,4,4];
// print_r(removeDuplicates($arr));

$arr = [1,2,2,3,1,4];

$freq = [];

foreach($arr as $value){
        if(isset($freq[$value])){
                $freq[$value]++;
        }else{
                $freq[$value] = 1;
        }
}

// print_r($freq);

interface fruits {
        public function getColor();

        // public function getTaste();
}
class Basket implements fruits{
        public $name;
        public $color;

        public function __construct($name, $color){
                $this->name = $name;
                $this->color = $color;

        }

        public function getColor(){
                return "The $this->name is $this->color in color";
        }
}

// $apple = new Basket('apple', 'red');
// echo $apple->getColor();

// function divide($x, $y){
//         if($y == 0){
//                 throw new Exception("Cannot divide by Zero");

//         }
//         return $x / $y;
// }

// try{
//         divide(5, 0);
// }catch(Exception $e){
//         echo "Error: $e->getMessage()";

//}

// echo "" <= 0;

$array = [
        "wasim" => 24,
        "Aneesha" => 22,
        "suhana" => 6
];

// print_r($array);

$i_array = ['volvo', 'Suzzuki','Honda'];

// echo $i_array[0];
// echo "<br>";

// foreach($i_array as &$car){
//         $car = 'renault';
//         echo "$car <br>";
// }
//unset($car);

$car = "redmi";
//echo $array['wasim'];
// var_dump($i_array);

$fruits = array("Apple", "Banana", "Cherry");
$new_fruit = "Orange";
// array_splice($fruits, 1,0, $new_fruit); // insert "Orange" at index 1

// print_r($fruits);

// listen  = silent;

function checkAnagaram($str1, $str2){

        if(strlen($str1) != strlen($str2)){
                return false;

        }
        $str1 = strtolower($str1);
        $str2 = strtolower($str2);
        $str_occ1 = [];
        $str_occ2 = [];
        for($i= 0; $i< strlen($str1); $i++){
                if(isset($str_occ1[$str1[$i]])){
                        $str_occ1[$str1[$i]]++;

                }else{
                        $str_occ1[$str1[$i]] = 1;
                }

        }

        for($i= 0; $i< strlen($str2); $i++){
                 if(isset($str_occ2[$str2[$i]])){
                        $str_occ2[$str2[$i]]++;

                }else{
                        $str_occ2[$str2[$i]] = 1;
                }

        }
        
        foreach($str_occ1 as $key => $value){
                if(! isset($str_occ2[$key])){
                        return false;

                }
                elseif($str_occ1[$key] != $str_occ2[$key]){
                        return false;
                }

        }
        return true;
        

        // print_r($str_occ);

}

// echo checkAnagaram("triangle","integral") ? " Anagaram" : "Not Anagaram" ;

// echo ;
// echo dirname(__DIR__);

/**
 * 
 * array_push() - add to the end of the array
 * 
 * array_unshift() - add one or more items to the beginning of an array
 * 
 * array_splice() - replace a portion of an array and replaces it with
 *                  new elements
 * 
 * array_merge() - merge two or more arrays
 * 
 */
$students = [
    ["name"=>"John","mark"=>90],
    ["name"=>"Sam","mark"=>45],
    ["name"=>"David","mark"=>75],
    ["name"=>"Alex","mark"=>60]
];
$highScorer = "";
$maxMark  = 0;
foreach($students as  $student){
//      echo $student['name'] . "\n";
     
     if( $student['mark'] > $maxMark){
        $maxMark = $student['mark'];
        $highScorer = $student['name'];
    }
}



//echo "Max mark student : " . $highScorer;
$movies = array(
  array(
    "title" => "Rear Window",
    "director" => "Alfred Hitchcock",
    "year" => 1954
  ),
  array(
    "title" => "Full Metal Jacket",
    "director" => "Stanley Kubrick",
    "year" => 1987
  ),
  array(
    "title" => "Mean Streets",
    "director" => "Martin Scorsese",
    "year" => 1973
  )
);

// print_r($movies);

function searchByDirector($movies, $directorName){

        foreach($movies as $movie){

                if(str_contains( $movie['director'], $directorName)){
                        return $movie['title'];

                }

}
        return "not available";


}  

// echo searchByDirector($movies, "Kubrick");
$cars = array (
  array("Volvo", 22, 18),
  array("BMW", 15, 13),
  array("Saab", 5, 2),
  array("Land Rover", 17, 15)
);

$carName;
$soldQuantity = 0;

foreach($cars as $car){
    if($car[2] > $soldQuantity){
        $soldQuantity = $car[2];
        $carName = $car[0];
    }
}
// print_r($cars);

// echo "Heighest sold car :" . $carName ."<br>" . "Sold Quantity :" . $soldQuantity; 
    
var_dump(ceil(4/3));