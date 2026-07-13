<?php

require "includes/init.php";


if(isset($_GET['id'])){
    $conn = Database::getConn();

    $companyId = Auth::companyId(); 
    $customerId = intval($_GET['id']);
    $customer = Customer::getCustomerById($conn, $companyId, $customerId);

    $customerName = $customer[0]['customer_name'];


}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $customerName = $_POST['customerName'];
    if($customerName == ""){
        $errors['customerName'] = 'CustomerName cannot be empty';
    }

    if(empty($errors)){
        $result = Customer::updateCustomer($conn, $companyId, $customerId, $customerName);
        if($result === true){
            Url::redirect('/customers.php');
        }else{
        $errors['customerName'] = $result;
    }
}
}

?>

<?php require "includes/header.php"?>
<div class="container mt-3">

    <div class="card card-primary ">

        <div class="card-header">
            <h3 class="card-title">Edit Customer</h3>
        </div>

        <div class="card-body">

            <form method="post">

                <div class="row">

                    <div class="col-md-6">

                        <label for="add-customer" class="form-label">
                            Customer Name
                        </label>

                        <input
                            type="text"
                           
                            name="customerName"
                            class="form-control <?= isset($errors['customerName']) ? 'is-invalid' : ''; ?>"
                            value="<?= htmlspecialchars($customerName); ?>">

                        <?php if (isset($errors['customerName'])): ?>
                            <div class="invalid-feedback">
                                <?= $errors['customerName']; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Update Customer

                        </button>

                        <a
                            href="customers.php"
                            class="btn btn-secondary ms-2">

                            Cancel

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>



<?php require "includes/footer.php";?>