<?php


class Dashboard
{
    // Today's Sales
    public static function todaySales(mysqli $conn, int $companyId): float
    {
        $sql = "SELECT COALESCE(SUM(soi.quantity * soi.unit_price),0) AS sales
                FROM sales_order_items soi
                INNER JOIN sales_order so
                    ON so.id = soi.sales_order_id
                WHERE so.company_id = ?
                AND so.created_at >= CURDATE()
                AND so.created_at < CURDATE() + INTERVAL 1 DAY";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $companyId);
        $stmt->execute();

        return (float)$stmt->get_result()->fetch_assoc()['sales'];
    }

    // Today's Profit
    public static function todayProfit(mysqli $conn, int $companyId): float
    {
        $sql = "SELECT COALESCE(
                    SUM((soi.unit_price - p.cost_price) * soi.quantity),
                    0
                ) AS profit
                FROM sales_order_items soi
                INNER JOIN sales_order so
                    ON so.id = soi.sales_order_id
                INNER JOIN products p
                    ON p.id = soi.product_id
                WHERE so.company_id = ?
                AND so.created_at >= CURDATE()
                AND so.created_at < CURDATE() + INTERVAL 1 DAY";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $companyId);
        $stmt->execute();

        return (float)$stmt->get_result()->fetch_assoc()['profit'];
    }

    // Today's Orders
    public static function todayOrders(mysqli $conn, int $companyId): int
    {
        $sql = "SELECT COUNT(*) AS orders_count
                FROM sales_order
                WHERE company_id = ?
                AND created_at >= CURDATE()
                AND created_at < CURDATE() + INTERVAL 1 DAY";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $companyId);
        $stmt->execute();

        return (int)$stmt->get_result()->fetch_assoc()['orders_count'];
    }

    // Today's Items Sold
    public static function todayItemsSold(mysqli $conn, int $companyId): int
    {
        $sql = "SELECT COALESCE(SUM(soi.quantity),0) AS items
                FROM sales_order_items soi
                INNER JOIN sales_order so
                    ON so.id = soi.sales_order_id
                WHERE so.company_id = ?
                AND so.created_at >= CURDATE()
                AND so.created_at < CURDATE() + INTERVAL 1 DAY";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $companyId);
        $stmt->execute();

        return (int)$stmt->get_result()->fetch_assoc()['items'];
    }

    public static function getTopSellingProducts($conn, $companyId){
        $sql = "SELECT
                    p.product_name,
                    SUM(soi.quantity) AS total_sold
                FROM sales_order_items soi
                INNER JOIN products p
                    ON p.id = soi.product_id
                INNER JOIN sales_order so
                    ON so.id = soi.sales_order_id
                WHERE so.company_id = ?
                AND so.created_at >= CURDATE()
                AND so.created_at < CURDATE() + INTERVAL 1 DAY
                GROUP BY p.id, p.product_name
                ORDER BY total_sold DESC
                LIMIT 5;";

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
    //Get stock report from Inventory aand Product table
    public static function lowStockProducts(mysqli $conn, int $companyId, int $threshold = 5): array
            {
                $sql = "SELECT
                        p.product_name,
                        COALESCE(i.quantity_available, 0) AS quantity_available
                    FROM products p
                    LEFT JOIN inventory i
                        ON p.id = i.product_id
                        AND i.company_id = p.company_id
                    WHERE p.company_id = ?
                    AND COALESCE(i.quantity_available, 0) < ?
                    ORDER BY quantity_available ASC, p.product_name";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $companyId, $threshold);
                $stmt->execute();

                return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            }

    public static function getRecentsales($conn, $companyId,$limit = 5){
        $sql = "SELECT
                        so.id,
                        so.invoice_number,
                        SUM(soi.quantity * soi.unit_price) AS invoice_total
                    FROM sales_order so
                    JOIN sales_order_items soi
                        ON soi.sales_order_id = so.id
                    WHERE so.company_id = ?
                    AND so.created_at >= CURDATE()
                    AND so.created_at < CURDATE() + INTERVAL 1 DAY
                    GROUP BY so.id, so.invoice_number
                    ORDER BY so.created_at DESC
                    limit  ?;";
    
        $stmt = $conn->prepare($sql);

                if (!$stmt) {
                    throw new Exception($conn->error);
                }
                $stmt->bind_param("ii",$companyId,$limit);
                $stmt->execute();
               
                $result = $stmt->get_result();
                $result = $result->fetch_all(MYSQLI_ASSOC);
                return $result;
    }

    public static function getTotalCompanies($conn){
        $sql = "SELECT Count(*)  As total from company ";

        $stmt = $conn->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result();
        $result = $result->fetch_assoc()['total'];
        return $result;
    }
    public static function getActiveCompanies(mysqli $conn): int
        {
            $sql = "SELECT COUNT(*) AS total
                    FROM company
                    WHERE subscription_status = 'ACTIVE'";

            $result = $conn->query($sql);

            return (int) $result->fetch_assoc()['total'];
        }

    public static function getSuspendedCompanies(mysqli $conn): int
        {
            $sql = "SELECT COUNT(*) AS total
                    FROM company
                    WHERE subscription_status = 'SUSPENDED'";

            $result = $conn->query($sql);

            return (int) $result->fetch_assoc()['total'];
        }

    public static function getTotalUsers(mysqli $conn): int
        {
            $sql = "SELECT COUNT(*) AS total
                    FROM users";

            $result = $conn->query($sql);

            return (int) $result->fetch_assoc()['total'];
        }
    
    public static function getNewCompaniesThisMonth(mysqli $conn): int
        {
            $sql = "SELECT COUNT(*) AS total
                    FROM company
                    WHERE MONTH(created_at) = MONTH(CURDATE())
                    AND YEAR(created_at) = YEAR(CURDATE())";

            $result = $conn->query($sql);

            return (int) $result->fetch_assoc()['total'];
        }

    public static function getRecentCompanies(mysqli $conn): array
        {
            $sql = "SELECT
                        c.id,
                        c.company_name,
                        c.subscription_status,
                        c.created_at,
                        COUNT(u.id) AS total_users

                    FROM company c

                    LEFT JOIN users u
                        ON c.id = u.company_id

                    GROUP BY
                        c.id,
                        c.company_name,
                        c.subscription_status,
                        c.created_at

                    ORDER BY c.created_at DESC

                    LIMIT 5";

            $result = $conn->query($sql);

            return $result->fetch_all(MYSQLI_ASSOC);
        }

    public static function getRecentUsers(mysqli $conn): array
        {
            $sql = "SELECT
                        u.name,
                        c.company_name

                    FROM users u

                    INNER JOIN company c
                        ON u.company_id = c.id

                    ORDER BY u.id DESC

                    LIMIT 5";

            $result = $conn->query($sql);

            return $result->fetch_all(MYSQLI_ASSOC);
        }
}