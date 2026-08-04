<?php
/**
 * ProductService for Product realted actions
 */

Class Product{

    public static function getAll($conn, $sessionCompany){
            // $sql = "SELECT
            //             p.*,
            //             c.category_name,
            //             COALESCE(SUM(poi.quantity), 0) AS total_quantity
            //         FROM products p
            //         INNER JOIN categories c
            //             ON p.category_id = c.id
            //         LEFT JOIN purchase_order_items poi
            //             ON p.id = poi.product_id
            //         WHERE p.company_id = ?
            //         GROUP BY
            //             p.id,
            //             p.product_name,
            //             c.category_name;";
            $sql = "SELECT
                        p.*,
                        c.category_name,
                        COALESCE(inv.quantity_available, 0) total_quantity
                    FROM products p
                    INNER JOIN categories c
                        ON p.category_id = c.id
                    LEFT JOIN inventory inv
                        ON p.id = inv.product_id
                    WHERE p.company_id = ?
                    GROUP BY
                        p.id,
                        p.product_name,
                        c.category_name;";

            $stmt = $conn->prepare($sql);

                
                $stmt->bind_param("i",$sessionCompany);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
            
    }

    public static function getTotal($conn, $companyId){
       $sql = "SELECT count(*) As total from products where company_id = ?";

       $stmt = $conn->prepare($sql);
       $stmt->bind_param("i",
                        $companyId);
       $stmt->execute();
       $result = $stmt->get_result();
       $row = $result->fetch_assoc();


       return $row['total'];

    }
    public static function  getProduct($conn,$sessionCompany,  $limit, $offset){

                $sql = "SELECT
                        p.*,
                        c.category_name,
                        COALESCE(inv.quantity_available, 0) total_quantity
                    FROM products p
                    
                    INNER JOIN categories c
                        ON p.category_id = c.id
                    LEFT JOIN inventory inv
                        ON p.id = inv.product_id
                    WHERE p.company_id = ?
                    GROUP BY
                        p.id,
                        p.product_name,
                        c.category_name
                    LIMIT ?
                    OFFSET ? ";

                $stmt = $conn->prepare($sql);

                
                $stmt->bind_param("iii",
                            $sessionCompany,
                            $limit,
                            $offset);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;

    }

    public static function getAvaliableProducts($conn, $companyId){
        $sql = "SELECT p.id, p.product_name, inventory.quantity_available from products p  join inventory  on p.id = inventory.product_id 
                        where inventory.company_id = ? and inventory.quantity_available > 0;";

        $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception($conn->error);
                }
                $stmt->bind_param("i",$companyId);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;


    }


    public static function getById($conn, $productId, $companyId){
        $sql = "SELECT
                        p.*,
                        c.category_name as category_name
                    FROM products p
                    INNER JOIN categories c
                        ON p.category_id = c.id
                    WHERE p.company_id = ?  and p.id= ?
                    GROUP BY
                        p.id,
                        p.product_name,
                        c.category_name;";
        if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("ii",$companyId, $productId);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
                
            }
            else{
                false;
            }


    }
    public static function addProducts($conn, $company_id,$category_id, $productCode,$productName,$costPrice, $sellingPrice){

        
            $sql = "INSERT INTO Products
                        (company_id, category_id, product_code, product_name,cost_price, selling_price) 
                        values(?, ?, ?, ?, ?,?)";
                


             $stmt = $conn->prepare($sql);

        
                $stmt->bind_param("iissdd", $company_id,$category_id, $productCode,$productName,$costPrice, $sellingPrice);
                if (!$stmt->execute()) {
                    die($stmt->error);
                }
                return true;
                
                
            
    }
    public static function updateProducts(
                                            $conn,
                                            $productId,
                                            $companyId,
                                            $productCode,
                                            $productName,
                                            $productCategory,
                                            $costPrice,
                                            $sellingPrice
                                        ) {

                $sql = "
                    UPDATE Products
                    SET
                        product_code = ?,
                        product_name = ?,
                        category_id = ?, 
                        cost_price = ?,
                        selling_price = ?
                    WHERE id = ?
                    AND company_id = ?
                ";

                $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception($conn->error);
                }

                $stmt->bind_param(
                    "ssiddii",
                    $productCode,
                    $productName,
                    $productCategory,
                    $costPrice,
                    $sellingPrice,
                    $productId,
                    $companyId
                );

                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }

                $stmt->close();

                return true;
}
    
    


    public static function delete($conn, $companyId, $productId){
        $sql = "DELETE  from products  where company_id =? and id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii",$companyId,$productId);
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