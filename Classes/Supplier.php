<?php 

Class Supplier{

        public static function getSuppliers($conn, $session_company ){
             $sql = "SELECT * FROM Suppliers where company_id = ? ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i",$session_company);
           
            $stmt->execute();
            $result = $stmt->get_result();
            $result = $result->fetch_all(MYSQLI_ASSOC);
            return $result;
            
            
        }
        
     public static function getSupplierById($conn, $companyId, $supplierId){

            $sql = "SELECT * from suppliers where company_id = ? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                    "ii",
                    $companyId,
                    $supplierId);
                



                $stmt->execute();
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);

                return $result;
    }

    


    public static function createSupplier($conn, $companyId, $supplierName){

        $sql = "INSERT INTO suppliers(company_id, supplier_name)
                            values(?, ?);";
        
        $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception($conn->error);
                }
                $stmt->bind_param("is",$companyId, $supplierName);
                try{
                 $stmt->execute();
                 return true;

            }catch (mysqli_sql_exception $e) {

                // Duplicate entry
                if ($e->getCode() == 1062) {
                    return "Supplier already exists.";
                }

                throw $e;
            }
    }

    public static function updateSupplier($conn,$companyId,$supplierId, $supplierName){
            $sql = "UPDATE suppliers set supplier_name = ? where company_id = ? and id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                            "sii",
                            $supplierName,
                            $companyId,
                            $supplierId

            );
            try{
                 $stmt->execute();
                 return true;

            }catch (mysqli_sql_exception $e) {

                // Duplicate entry
                if ($e->getCode() == 1062) {
                    return "Supplier Name already exists.";
                }

                throw $e;
            }
           

       
        }

    public static function delete($conn, $companyId, $supplierId){
        $sql = "DELETE  from suppliers  where company_id =? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii",$companyId,$supplierId);
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