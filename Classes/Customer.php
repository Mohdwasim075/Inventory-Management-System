<?php

Class Customer{

    public static function addCustomer($conn, $companyId, $customerName){

        $sql = "INSERT INTO customer(company_id, customer_name)
                        values(? ,? );";

        $stmt = $conn->prepare($sql);

         $stmt->bind_param('is', $companyId, $customerName);

        $stmt->execute();

        $stmt->close();
        return true;

    }

    public static function getCustomers($conn, $companyId){
         $sql = "SELECT id, customer_name FROM customer WHERE company_id = ? ";

                $stmt = $conn->prepare($sql);

            
                $stmt->bind_param("i",$companyId);
                $stmt->execute();
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
    
             
    }
     public static function getCustomerById($conn, $companyId, $customerId){

            $sql = "SELECT * from customer where company_id = ? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                    "ii",
                    $companyId,
                    $customerId);
                



                $stmt->execute();
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);

                return $result;
    }

   public static function createCustomer($conn, $companyId, $customerName){

        $sql = "INSERT INTO customer(company_id, customer_name)
                            values(?, ?);";
        
        $stmt = $conn->prepare($sql);

               
                $stmt->bind_param("is",$companyId, $customerName);
                try{
                 $stmt->execute();
                 return true;

            }catch (mysqli_sql_exception $e) {

                // Duplicate entry
                if ($e->getCode() == 1062) {
                    return "Customer already exists.";
                }

                throw $e;
            }
    }

      public static function updateCustomer($conn,$companyId,$customerId, $customerName){
            $sql = "UPDATE customer set customer_name = ? where company_id = ? and id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                            "sii",
                            $customerName,
                            $companyId,
                            $customerId

            );
            try{
                 $stmt->execute();
                 return true;

            }catch (mysqli_sql_exception $e) {

                // Duplicate entry
                if ($e->getCode() == 1062) {
                    return "Customer Name already exists.";
                }

                throw $e;
            }
           

       
        }

    public static function delete($conn, $companyId, $customerId){
        $sql = "DELETE  from customer  where company_id =? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii",$companyId,$customerId);
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