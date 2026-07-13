<?php
require "includes/init.php";

Auth::requireLogin();
Auth::requireRole('USER');

$conn = require 'includes/db.php';

$companyId = Auth::companyId();

$suppliers = Supplier::getSuppliers($conn, $companyId);


?>

<?php require "includes/header.php"; ?>

<div class="card card-primary mt-3">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <h3 class="card-title mb-0">
                <i class="fas fa-boxes me-2"></i>
                Suppliers
            </h3>

           

        </div>

    </div>
   <div class= "mt-3">
             <a  href= "add-supplier.php"
                      class=" add-button btn btn-primary"> 
                       <i class="bi bi-plus-circle"></i> Add Supplier
                    </a>
        
       </div>
     

    <div class="card-body">
        
        <?php if (isset($_SESSION['suppl'])): ?>

            <div class="alert alert-<?= $_SESSION['suppl']['type']; ?> alert-dismissible fade show">

                <?= htmlspecialchars($_SESSION['suppl']['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">

                </button>

            </div>

            <?php unset($_SESSION['suppl']); ?>

        <?php endif; ?>

        <?php if (empty($suppliers)): ?>

            <div class="alert alert-info mb-0">

                No suppliers found.

            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="70">S.No</th>
                            <th>Supplier Name</th>
                            <th width="">Edit</th>
                            <th width="">Delete</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($suppliers as $index => $supplier): ?>

                            <tr>

                                <td><?= $index + 1; ?></td>

                                <td>

                                    <?= htmlspecialchars($supplier['supplier_name']); ?>

                                </td>

                                <td>

                                    <a
                                        href="edit-supplier.php?id=<?= $supplier['id']; ?>"
                                        class="btn btn-success btn-sm">

                                        <i class="bi bi-pencil-square"></i>

                                        Edit

                                    </a>

                                </td>

                                <td>

                                    <a
                                        href="delete-supplier.php?id=<?= $supplier['id']; ?>"
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



<?php require "includes/footer.php"; ?>