<?php



Class PasswordReset{

    private CONST TOKEN_BYTES = 32;



    //save the hashed token , expires at(time) with the resp userId
    public static function sendResetLink($conn, $userId){

        //generate an random token for password reset link
        $token = bin2hex(random_bytes(self::TOKEN_BYTES));

         $resetLink =
        "http://localhost/reset-password.php?token=" .
        urlencode($token);
        //hash the token to save to database
        $token_hash = hash('sha256', $token);

        $expires = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $expires->modify('+5 minutes ');


        $expiresAt = $expires->format('Y-m-d H:i:s');
        
        $sql = "INSERT INTO password_resets(user_id, token_hash,expires_at)
                        values(?, ?, ?) ;";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iss",
            $userId,
            $token_hash,
            $expiresAt
        );
        
        $result = $stmt->execute();
        return $resetLink;



    }

    //validate token of the URL 

    public static function findByToken($conn, $urlToken){ 

        $tokenHash = hash('sha256',$urlToken);
        
        $sql = "SELECT
                        id,
                        user_id,
                        expires_at,
                        used
                    FROM password_resets
                    WHERE token_hash = ?
                    LIMIT 1;";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "s",
            $tokenHash
        );

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null ;
       

    }

    //delete the reset token after successful password reset
    public static function deleteToken($conn, $urlToken){

        $tokenHash = hash('sha256',$urlToken);

        $sql = "DELETE FROM password_resets where token_hash = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "s",
            $tokenHash
        );

        $stmt->execute();

    }
    
}

