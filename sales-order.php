<?php

require "includes/init.php";

$conn = require "includes/db.php";

Auth::requireLogin();
Auth::requireRole('USER');


$companyId = Auth::companyId();

$invoices = Sales::getSalesInvoices($conn, $companyId);

var_dump(empty($invoices));

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

                <?php foreach ($invoices as $invoice): ?>

                    <tr>

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

</div>

<?php require "includes/footer.php"; ?>