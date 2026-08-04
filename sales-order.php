<?php

require "includes/init.php";

$conn = require "includes/db.php";

Auth::requireLogin();
Auth::requireRole('USER');


$companyId = Auth::companyId();
$paginator = new Paginator($_GET["page"] ?? 1 , 3 , Sales::getTotalSales($conn, $companyId) );
$invoices = Sales::getSalesInvoices($conn, $companyId,  $paginator->limit, $paginator->offset);

// var_dump(empty($invoices));

require "includes/header.php";

?>

<div class="card card-primary">

    <div class="card-header">
        <h3 class="card-title">
            Sales Invoices
        </h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Invoice Date</th>
                    <th class="text-right">Invoice Amount</th>
                </tr>
            </thead>
            <?php if(empty($invoices)): ?>
                 <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No sales found.
                                        </td>
                                    </tr>
            <?php else : ?>
            <tbody>
                <?php $index = $paginator->offset;?>
                <?php foreach ($invoices as $id => $invoice): ?>
                        <?php $index++; ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($index)?>
                        </td>
                        <td>
                            <?= htmlspecialchars($invoice['invoice_number']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($invoice['customer_name']) ?>
                        </td>

                        <td>
                            <?= date("d-m-Y", strtotime($invoice['created_at'])) ?>
                        </td>

                        <td class="text-right">
                            <?= number_format($invoice['invoice_amount'], 2) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>
            <?php endif;?>

        </table>

    </div>
    <?php require "./includes/pagination.php";?>
    

</div>

<?php require "includes/footer.php"; ?>