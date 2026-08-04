<?php

Class Company{

    public static function createCompany($conn,$companyName, $subscriptionStatus= "ACTIVE" ){
        $sql = "INSERT INTO company
                (company_name, subscription_status)
                VALUES (?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $companyName,
            $subscriptionStatus
        );

        if ($stmt->execute()) {
            $stmt->close();
            $lastId = $conn->insert_id;
            return $lastId;
        } else {

            $stmt->close();
           return false;

        }

       

    }
    public static function getCompanies($conn){
        $sql = "SELECT *
                        FROM company
                        ORDER BY company_name";

        $stmt = $conn->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();
        $companies = $result->fetch_all(MYSQLI_ASSOC);
        return $companies;
    }

    public static function getCompanyId($conn, $companyId){
        $sql = "SELECT *
                        FROM company
                        where id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $companyId);
        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        return $result;

    }

    public static function getCompanyStatus($conn, $companyId){
        
        $sql = "SELECT subscription_status from company where id = ?";


        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "i",
            $companyId
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $result =  $result->fetch_assoc()['subscription_status'];
        return $result;

    }
    /**
     * Get total No of companies 
     * 
     * 
     */
    public static function updateCompany($conn, $companyName, $subscriptionStatus, $companyId){
        $sql = "UPDATE  company
                SET company_name = ?,
                subscription_status = ?
                where id= ?";

         $stmt = $conn->prepare($sql);



    $stmt->bind_param(
        "ssi",
        $companyName,
        $subscriptionStatus,
        $companyId
    );

    $result = $stmt->execute();

    $stmt->close();

    return $result;

    }

    public static function delete($conn, $companyId){

        $sql = "DELETE  from company  where id =? ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i",$companyId);
            try{
                 $stmt->execute();
                 return [
                    'success' => true,
                 ];

            }catch(mysqli_sql_exception $e){
                if($e->getCode() == 1451){
                    return [
                        'success' => false
                    ];
                }
                throw $e;
            }

    }
}