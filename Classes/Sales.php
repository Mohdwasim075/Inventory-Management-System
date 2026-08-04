<?php

Class Sales{

            public static function addSalesItems($conn, $companyId, $customerId, $salesItems){

            $sql = "INSERT INTO sales_order(company_id, customer_id)
                            values(?, ?);";

            
            if(! $stmt= $conn->prepare($sql)){
                    die("Failed to prepare statement: " . $conn->error);
                }

            $stmt->bind_param('ii',$companyId, $customerId);

            $stmt->execute();
            
            $sql = "UPDATE sales_order SET invoice_number= ? WHERE id = ? ;";

            $salesOrderId = $conn->insert_id;
            $invoiceNumber = "INV-" . date("Ymd") . "-" . str_pad($salesOrderId, 4, "0", STR_PAD_LEFT);

                
            if(! $stmt= $conn->prepare($sql)){
                    die("Failed to prepare statement: " . $conn->error);
                }
            $stmt->bind_param('si',$invoiceNumber,$salesOrderId);

            $stmt->execute();

            //add sales item to the sales_order_items table
            $sql = "INSERT INTO sales_order_items
                            (sales_order_id,product_id,quantity,unit_price
                            )
                            VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            foreach ($salesItems as $item) {

                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $unitPrice = floatval($item['sale_price']);

                $stmt->bind_param(
                    "iiid",
                    $salesOrderId,
                    $productId,
                    $quantity,
                    $unitPrice
                );

                $stmt->execute();
            }

            
            
            //update the stock transactions after sales order
            

            $sql = "INSERT INTO `stock _transaction`(company_id,product_id,transaction_type,
                                                quantity_change,reference_table, reference_id)
                            values(?,?,?,?,?,?)";
        
            $transactionType = "SALE";
            $referenceTable = "SALE";
            $stmt = $conn->prepare($sql);

            foreach ($salesItems as $item) {

                $productId = $item['product_id'];
                $quantity =$item['quantity'];
                $salePrice = $item['sale_price'];

                $stmt->bind_param(
                    "iisisi",
                    $companyId,
                    $productId,
                    $transactionType,
                    $quantity,
                    $referenceTable,
                    $salesOrderId
                );

                $stmt->execute();
            }
            //update the inventory
            $productTotals =[];
   
            foreach($salesItems as $item ){
                $productId = $item['product_id'];

                if(! isset($productTotals[$productId])){
                    $productTotals[$productId] = 0;
            }

            $productTotals[$productId] += $item['quantity'];
        }
        

            $stmt = $conn->prepare("
                                    UPDATE  inventory 
                                    SET quantity_available = quantity_available - ? 
                                     WHERE company_id = ? and product_id=  ?  and quantity_available >= ?;
                        
                                    ");
            foreach ($productTotals as $productId => $qty) {

                $stmt->bind_param(
                    "iiii",
                    $qty,
                    $companyId,
                    $productId,
                    $qty
                  
                );

                $stmt->execute();
                if ($stmt->affected_rows === 0) {
                        throw new Exception("Insufficient stock for product ID: $productId");
                    }
            }
    }
            public static function getSalesInvoices( $conn,  $companyId, $limit, $offset)
                {
                    $sql = "SELECT
                                so.id,
                                so.invoice_number,
                                c.customer_name,
                                so.created_at,
                                COALESCE(SUM(soi.quantity * soi.unit_price),0) AS invoice_amount
                            FROM sales_order so
                            INNER JOIN customer c
                                ON so.customer_id = c.id
                            LEFT JOIN sales_order_items soi
                                ON so.id = soi.sales_order_id
                            WHERE so.company_id = ?
                            GROUP BY
                                so.id,
                                so.invoice_number,
                                c.customer_name,
                                so.created_at
                            ORDER BY so.created_at DESC
                            limit ?
                            offset ?";

                    $stmt = $conn->prepare($sql);

                    $stmt->bind_param("iii", 
                                    $companyId,
                                    $limit,
                                    $offset);

                    $stmt->execute();

                    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                }

        public static function getTotalSales($conn, $companyId){
            $sql = "SELECT count(*) As total from sales_order where company_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i",
                                $companyId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();


       return $row['total'];
        }

            }


