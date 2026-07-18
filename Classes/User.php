<?php

Class User{

    public $id;
    public $username;
    public $password;

    public static function Authenticate($conn, $username, $password){



            $sql = "SELECT id,company_id, name, email ,role,  status , password FROM Users where name = ?;";
            if(!$stmt = $conn->prepare($sql)){
                die("Failed to prepare statement " . $conn->error);

            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            
            if (!$user || !password_verify($password, $user['password'])) {
                $stmt->close();
                return false;
            }

            $_SESSION['user'] = [
                'id'         => $user['id'],
                'company_id' => $user['company_id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'role'       => $user['role']
          ];

            $stmt->close();
            return true;
    

    }

    //check if user email id in the database
    public static function findUserByEmail($conn, $email){
        $sql = "SELECT id FROM users WHERE email = ?";

         $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();
            if($result->num_rows === 1){
                $user = $result->fetch_assoc();
                return [
                    'userId' => $user['id']
                ];
            }
            return false;



    }

    //reset user password 

    public static function resetUserPassword($conn,$userId , $password){

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "UPDATE users SET password=? WHERE id=?"
            );

            $stmt->bind_param("si", $hashedPassword, $userId);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }



    }

    /**
     * Get all the users from the database with their detials
     * 
     *   
     */
    //
    public static function getAllUser(object  $conn){

    $sql = " SELECT  company.company_name,  users.*  from users inner join company on  company.id = users.company_id where users.role = ?";
    if(!$stmt = $conn->prepare($sql)){
        die('Failed to prepare statement: ' . $conn->error);
    }
    $role = "USER";
    $stmt->bind_param('s',$role);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    return $users;


    }

    public static function getUserProfile($conn, $userId){
        $sql = "SELECT
                        u.id,
                        u.name,
                        u.email,
                        u.contact_no,
                        u.role,
                        u.created_at,
                        c.company_name
                    FROM users u
                    INNER JOIN company c
                        ON c.id = u.company_id
                    WHERE u.id = ?;";
           if(! $stmt= $conn->prepare($sql)){
            die("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result = $result->fetch_all(MYSQLI_ASSOC)){
            return $result ;

        }
        return false;
        

    }

    //function used in admin edit user form
    public static function getUserById($conn, $userID){

        $sql = "SELECT * FROM users where id= ? ";
        if(! $stmt= $conn->prepare($sql)){
            die("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result = $result->fetch_all(MYSQLI_ASSOC)){
            return $result ;

        }
        return false;

    }

    //get the user current password

    public static function getUserPassword($conn, $userId){

        $sql = "SELECT password FROM users where id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
                'i',
                $userId);

        $stmt->execute();
        $result = $stmt->get_result();
        if($result = $result->fetch_assoc()){

    
            return $result['password'] ;

        }
        return false;


    }

    // update the new password set by the user

    public static function updatePassword($conn, $userId,  $passwordHash){

        
            $stmt = $conn->prepare(
                "UPDATE users SET password=? WHERE id=?"
            );

            $stmt->bind_param("si", $passwordHash, $userId);
            if($stmt->execute()){
                return [
                    "success" => true
                ];
            }else{
                return false;
            }
    }


    public static function updateUserProfile($conn,$name, $email, $role,$companyId,  $userId ){
         $sql = "UPDATE users
                SET
                    name = ?,
                    email = ?,
                    role = ?,
                    company_id = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssii",
            $name,
            $email,
            $role,
            $companyId,
            $userId
        );

        return $stmt->execute();
    }

    //update the user profile changed 

    public static function updateProfileChanges($conn,$data ){

    $sql = "UPDATE users
            SET name = ?,
                email = ?,
                contact_no = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssdi",
        $data['name'],
        $data['email'],
        $data['contactNumber'],
        $data['userId']
    );

    return $stmt->execute();

    }

    /**
     * Add new user to the database
     * 
     * @param object $conn;
     * 
     * @param $name, $email, $password, $role, $status;
     * 
     *
     */

    public static function addNewUser( $conn, $name, $email, $password, $role,  $status){

    $sql = "INSERT INTO users(name, email, password, role, status )
                        values(?, ?, ?, ? ,?);";

    if(! $stmt = $conn->prepare($sql)){
        die("Falied to prepare statement: ". $conn->error);

    }
    $stmt->bind_param(
        "sssss",
        $name,
        $email,
        $password,
        $role,
        $status);
    

   if(! $result = $stmt->execute()){
        return false;

   }


    return true;

}
}
