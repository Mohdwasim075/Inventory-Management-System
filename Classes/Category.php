<?php 

Class Category{

        public static function getCategories($conn, $session_company){

                $sql = "SELECT id, category_name FROM Categories WHERE company_id = ? ";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i",$session_company);
                $stmt->execute();
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
                
                    
                   


            }
        public static function setCategory($conn,$sessionCompany, $new_category){
        $sql = "INSERT INTO Categories(company_id, category_name) 
                VALUES (?, ?) ";

            $stmt = $conn->prepare($sql);


            $stmt->bind_param("is",$sessionCompany, $new_category);
            try{

                $stmt->execute();

                return true;

            } catch (mysqli_sql_exception $e) {

                // Duplicate entry
                if ($e->getCode() == 1062) {
                    return "Category already exists.";
                }

                throw $e;
            }
        }

        public static function updateCategory($conn,$companyId,$categoryId, $categoryName){
            $sql = "UPDATE categories set category_name = ? where company_id = ? and id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                            "sii",
                            $categoryName,
                            $companyId,
                            $categoryId

            );
            try{
                 $stmt->execute();
                 return true;

            }catch (mysqli_sql_exception $e) {

                // Duplicate entry
                if ($e->getCode() == 1062) {
                    return "Category already exists.";
                }

                throw $e;
            }
           

       
        }
        
        
        public static function getCategoryById($conn, $companyId, $categoryId){

            $sql = "SELECT * from categories where company_id = ? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                    "ii",
                    $companyId,
                    $categoryId);
                



                $stmt->execute();
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);

                return $result;
    }

    public static function delete($conn, $companyId, $categoryId){
        $sql = "DELETE  from categories  where company_id =? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii",$companyId,$categoryId);
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