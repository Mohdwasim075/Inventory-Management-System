<?php require "includes/init.php";?>
<?php

$conn = require "includes/db.php";


    $companyId = Auth::companyId();
    $customers = Customer::getCustomers($conn, $companyId);
    // var_dump($customers);




?>
<?php require "includes/header.php" ?>
<div class="card card-primary mt-2">
     <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes mr-1"></i>
            Customers
        </h3>
    </div>

   
        
    <div>
         <a
                href="add-customer.php"
                class="add-button btn btn-primary btn mt-3">

                <i class="bi bi-plus-circle"></i>
                Add Customer

            </a>

    </div>
       
        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">

                <ul class="mb-0">

                    <?php foreach ($errors as $error): ?>

                        <li><?= htmlspecialchars($error) ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

       

        <!-- Customer List -->
           <?php if (isset($_SESSION['customer'])): ?>

            <div class="alert alert-<?= $_SESSION['customer']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['customer']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['customer']); ?>

        <?php endif; ?>

        <?php if (empty($customers)): ?>
            <div class="container">
                <div class=" alert alert-info mb-2">

                No Customers found.

            </div>


            </div>
             
          


        <?php else: ?>
            <div class="container">
                <div class="table-responsive">

                <table class="table table-striped table-hover">

                    <thead>

                        <tr>

                            <th>S.No</th>

                            <th>Customer Name</th>
                            <th></th>
                            <th></th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($customers as $index => $customer): ?>

                        <tr>

                            <td><?= $index + 1 ?></td>

                            <td>

                                <?= htmlspecialchars($customer['customer_name']) ?>

                            </td>
                             <td>

                                    <a
                                        href="edit-customer.php?id=<?= $customer['id']; ?>"
                                        class="btn btn-success btn-sm">

                                        <i class="bi bi-pencil-square"></i>

                                        Edit

                                    </a>

                                </td>

                                <td>

                                    <a
                                        href="delete-customer.php?id=<?= $customer['id']; ?>"
                                        class="btn btn-danger btn-sm delete">

                                        <i class="bi bi-trash"></i>

                                        Delete

                                    </a>

                                </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

            </div>

            

</div>


<?php require "includes/footer.php" ?>