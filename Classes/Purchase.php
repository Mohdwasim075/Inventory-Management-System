<?php 
Class Purchase{

    public static function getSupplier($conn, $sessionCompany){
            $sql = "SELECT id, supplier_name from suppliers where company_id = ?;";

            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("i",$sessionCompany);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
                
            }
            return false;
            
    }
    
    public static function getPurchaseList($conn, $sessionCompany, $limit, $offset){

        $sql = "SELECT 
                    po.id,
                    po.po_number,
                    po.status,
                    po.created_at,
                    po.total_amount
                    FROM purchase_orders po
                    WHERE company_id = ?
                    ORDER BY po.created_at DESC
                    LIMIT ?
                    OFFSET ?";
        
        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
                    "iii",
                    $sessionCompany,
                    $limit,
                    $offset
        );

        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_all(MYSQLI_ASSOC);
        return $result;

    }

    public static function getPurchaseOrder($conn, $sessionCompany){
            $sql = "SELECT
                        po.id,
                        po.po_number,
                        po.status,
                        po.created_at,
                        COALESCE(SUM(poi.quantity * poi.unit_cost),0) AS total_amount
                    FROM purchase_orders po
                    LEFT JOIN purchase_order_items poi
                        ON po.id = poi.purchase_order_id
                    WHERE po.company_id = ?
                    GROUP BY
                        po.id,
                        po.po_number,
                        po.status,
                        po.created_at
                    ORDER BY po.created_at DESC;";

            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("i",$sessionCompany);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
                
            }
            return false;
    }

    public static function updatePurchaseOrderTotal($conn, $orderId)
{
    $sql = "UPDATE purchase_orders
            SET total_amount = (
                SELECT COALESCE(SUM(quantity * unit_price),0)
                FROM purchase_order_items
                WHERE purchase_order_id = ?
            )
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $orderId, $orderId);

    return $stmt->execute();
}

    public static function findPurchaseOrder($conn, $companyId, $id){

                $sql = "SELECT
                                po.id,
                                po.po_number,
                                po.status,
                                po.total_amount,
                                po.created_at,
                                s.supplier_name
                            FROM purchase_orders po
                            JOIN suppliers s
                                ON s.id = po.supplier_id
                            WHERE po.id = ?
                            AND po.company_id = ?;";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii",$id,  $companyId);
                $stmt->execute();

                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

    public static function getPurchaseOrderItems($conn, $id ){

                $sql = "SELECT
                                p.product_code,
                                p.product_name,
                                poi.quantity,
                                poi.unit_cost,
                                (poi.quantity * poi.unit_cost) AS line_total
                            FROM purchase_order_items poi
                            JOIN products p
                                ON p.id = poi.product_id
                            WHERE poi.purchase_order_id = ?
                            ORDER BY p.product_name;";
        
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i",$id);
                $stmt->execute();

                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
    }
    public static function totalAmount(mysqli $conn, int $purchaseOrderId): float
        {
            $sql = "SELECT
                        COALESCE(SUM(quantity * unit_cost), 0) AS total_amount
                    FROM purchase_order_items
                    WHERE purchase_order_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $purchaseOrderId);
            $stmt->execute();

            return (float)$stmt->get_result()->fetch_assoc()['total_amount'];
}
    public static function getTotalPurchase($conn, $companyId){

        $sql = "SELECT count(*) as total from purchase_orders where company_id = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i",
                          $companyId);
        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();

        return $row['total'];

    }
    public static function addPurchaseOrder($conn,$companyId,$orderNumber, $orderSupplier,$orderStatus, $purchaseAmount){
           $sql = "INSERT INTO purchase_orders(company_id, po_number, supplier_id, status, total_amount)
                        values (?,?,?,?,?) ";
        $stmt = $conn->prepare($sql);
                // Bind parameters
                $stmt->bind_param("isisd", $companyId, $orderNumber,$orderSupplier,$orderStatus, $purchaseAmount);
                $stmt->execute();
                $orderId = $conn->insert_id;
                return $orderId;
            
            

       
    }
    public static function addPurchaseItems($conn,$companyId,$orderNumber, $orderItems){

        //add the purchase order items to the table
           $sql = "INSERT INTO purchase_order_items
                            (purchase_order_id,product_id,quantity,unit_cost
                            )
                            VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            foreach ($orderItems as $item) {

                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];

                $stmt->bind_param(
                    "iiid",
                    $orderNumber,
                    $productId,
                    $quantity,
                    $unitPrice
                );

                $stmt->execute();
            }
            //update the cost price of respective product
            $sql = "UPDATE products set cost_price = ? where company_id = ? and id = ?;";

            $stmt = $conn->prepare($sql);

            foreach ($orderItems as $item) {

                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];

                $stmt->bind_param(
                    "dii",
                    $unitPrice,
                    $companyId,
                    $productId,
                    
                );

                $stmt->execute();
            }
            //update the stock transactions
            
            

            $sql = "INSERT INTO `stock _transaction`(company_id,product_id,transaction_type,
                                                quantity_change,reference_table, reference_id)
                            values(?,?,?,?,?,?)";
        
            $transactionType = "PURCHASE";
            $referenceTable = "PURCHASE_ORDER";
            $stmt = $conn->prepare($sql);

            foreach ($orderItems as $item) {

                $productId = $item['product_id'];
                $quantity =$item['quantity'];
                $unitPrice = $item['unit_price'];

                $stmt->bind_param(
                    "iisisi",
                    $companyId,
                    $productId,
                    $transactionType,
                    $quantity,
                    $referenceTable,
                    $orderNumber
                );

                $stmt->execute();
            }
            //update the inventory
            $productTotals =[];
   
            foreach($orderItems as $item ){
                $productId = $item['product_id'];

                if(! isset($productTotals[$productId])){
                    $productTotals[$productId] = 0;
            }

            $productTotals[$productId] += $item['quantity'];
        }
        

            $stmt = $conn->prepare("
                                    INSERT INTO inventory
                                    (
                                        company_id,
                                        product_id,
                                        quantity_available
                                    )
                                    VALUES
                                    (
                                        ?, ?, ?
                                    )
                                    ON DUPLICATE KEY UPDATE
                                    quantity_available =
                                    quantity_available + VALUES(quantity_available)
                                    ");
            foreach ($productTotals as $productId => $qty) {

                $stmt->bind_param(
                    "iii",
                    $companyId,
                    $productId,
                    $qty
                );

                $stmt->execute();

            }
            return true;
    }

    





}