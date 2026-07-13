
$(document).ready(function () {

    // Username Validation
    $("#usercheck").hide();
    let usernameError = false;

    $("#username").keyup(function () {
        validateUsername();
    });

    function validateUsername() {
        let usernameValue = $("#username").val().trim();

        if (usernameValue.length === 0) {
            $("#usercheck").show();
            $("#usercheck").text("Username is required");
            $("#usercheck").css("color", "red");
            usernameError = true;
            return false;
        }
        else {
            $("#usercheck").hide();
            usernameError = false;
            return true;
        }
    }

    // Password Validation
    $("#passcheck").hide();
    let passwordError = false;

    $("#password").keyup(function () {
        validatePassword();
    });

    function validatePassword() {
        let passwordValue = $("#password").val().trim();

        if (passwordValue.length === 0) {
            $("#passcheck").show();
            $("#passcheck").html("Password is required");
            $("#passcheck").css("color", "red");
            passwordError = true;
            return false;
        }
        else if (passwordValue.length < 5 || passwordValue.length > 10) {
            $("#passcheck").show();
            $("#passcheck").html("password length must be between 5 and 10");
            $("#passcheck").css("color", "red");
            passwordError = true;
            return false;
        }
        else {
            $("#passcheck").hide();
            passwordError = false;
            return true;
        }
    }

    // Submit Form
    $("#login-form").submit(function (e) {
        let usernameValid = validateUsername();
        let passwordValid = validatePassword();

        if (!usernameError && !passwordError) {
            return true;
        } else {
            e.preventDefault();
            return false;
        }
    });

});
$(document).ready(function () {

    let rowIndex = 0;

    $("#addProduct").click(function () {
      

        let productId = $("#product_id").val();
        let productName = $("#product_id option:selected").text();

        let quantity = $("#quantity").val();
        let unitPrice = $("#unit_price").val();

        if (productId === "") {
            alert("Please select a product");
            return;
        }

        if (quantity <= 0 || quantity === "") {
            alert("Enter a valid quantity");
            return;
        }

        if (unitPrice <= 0 || unitPrice === "") {
            alert("Enter a valid price");
            return;
        }

        let total = quantity * unitPrice;

        let row = `
            <tr>

                <td>
                    ${productName}

                    <input
                        type="hidden"
                        name="items[${rowIndex}][product_id]"
                        value="${productId}">
                </td>

                <td>
                    ${quantity}

                    <input
                        type="hidden"
                        name="items[${rowIndex}][quantity]"
                        value="${quantity}">
                </td>

                <td>
                    ${unitPrice}

                    <input
                        type="hidden"
                        name="items[${rowIndex}][unit_price]"
                        value="${unitPrice}">
                </td>

                <td>${total}</td>

                <td>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm remove-product">
                        Remove
                    </button>
                </td>

            </tr>
        `;

        $("#purchaseItemsTable tbody").append(row);

        rowIndex++;

        $("#product_id").val("");
        $("#quantity").val("");
        $("#unit_price").val("");
    });

    $(document).on("click", ".remove-product", function () {

        $(this).closest("tr").remove();

    });

});
$(document).ready(function () {

   

    $("#addSaleProduct").click(function () {
         let rowIndex = 0;
         let customerName = $("#customer-Id").text();
        let productId = $("#product_id").val();
        let productName = $("#product_id option:selected").text();

        let quantity = $("#quantity").val();
        let salePrice = $("#sale_price").val();
        
        if(customerName === ""){
            alert("Please select Customer ");
            return;


        }
        if (productId === "") {
            alert("Please select a product");
            return;
        }

        if (quantity <= 0 || quantity === "") {
            alert("Enter a valid quantity");
            return;
        }

        if (salePrice <= 0 || salePrice === "") {
            alert("Enter a valid price");
            return;
        }

        let total = quantity * salePrice;

        let row = `
            <tr>

                <td>
                    ${productName}

                    <input
                        type="hidden"
                        name="items[${rowIndex}][product_id]"
                        value="${productId}">
                </td>

                <td>
                    ${quantity}

                    <input
                        type="hidden"
                        name="items[${rowIndex}][quantity]"
                        value="${quantity}">
                </td>

                <td>
                    ${salePrice}

                    <input
                        type="hidden"
                        name="items[${rowIndex}][sale_price]"
                        value="${salePrice}">
                </td>

                <td>${total}</td>

                <td>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm remove-product">
                        Remove
                    </button>
                </td>

            </tr>
        `;

        $("#salesItemsTable tbody").append(row);

        rowIndex++;

        $("#product_id").val("");
        $("#quantity").val("");
        $("#sale_price").val("");
    });

    $(document).on("click", ".remove-product", function () {

        $(this).closest("tr").remove();

    });

});


$("a.delete").on("click", function (e) {

    if (!confirm("Are you sure you want to delete?")) {
        e.preventDefault();
    }

});
