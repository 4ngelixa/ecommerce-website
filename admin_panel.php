<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bling Bling</title>
    <?php include "inc/head.inc.php"; ?>
    <?php include "process_admin.php"; ?>
    <link rel="stylesheet" href="css/admin_panel.css">
</head>

<body>
    <?php include "inc/nav.inc.php"; ?>

    <?php
    if ($_SESSION['fname'] != "admin") {
        echo "<script>alert('No Access');</script>";
        header("Location: index.php");
        exit();
    }
    ?>
    <main class="center-container">
        <div id="title">
            <h1 class="Rubrik-light">Welcome Back, Admin</h1>
        </div>
        <section class="flex-container">
            <div class="container mt-3 border">
                <h2 class="Rubrik-bold">Manage Members</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead">
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        $members = getAllMembers();
                        while ($member = $members->fetch_assoc()) {
                            if($member['fname'] != "admin"){
                                echo "<tr>";
                                echo "<td>" . $member['member_id'] . "</td>";
                                echo "<td>" . $member['fname'] . "</td>";
                                echo "<td>" . $member['email'] . "</td>";
                                echo "<td><button class='btn btn-danger' onclick='deleteMembers(" . $member['member_id'] . ")'>Delete</button></td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="container mt-3 border">
                <h2 class="Rubrik-bold">Manage Products</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead">
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                $products = getAllProducts();
                while ($product = $products->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $product['product_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($product['pname']) . "</td>";
                    echo "<td>
                            <form id='updatePriceForm_" . $product['product_id'] . "' onsubmit='updateProduct(" . $product['product_id'] . ", this); return false;'>
                            <label for='price_" . $product['product_id'] . "'>Price</label>
                            <input type='number' id='price_" . $product['product_id'] . "' name='price' value='" . $product['price'] . "' required>
                            </form>
                          </td>";
                    echo "<td>
                            <form id='updateStockForm_" . $product['product_id'] . "' onsubmit='updateProduct(" . $product['product_id'] . ", this); return false;'>
                            <label for='stock_" . $product['product_id'] . "'>stock</label>

                            <input type='number' id='stock_" . $product['product_id'] . "' name='stock' value='" . $product['stock'] . "' required>

                            </form>
                          </td>";
                    echo "<td>
                    <button class='btn btn-primary' onclick='(function() { updateProduct(" . $product['product_id'] . "); })();'>Update</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <?php include "inc/footer.inc.php"; ?>
    <script>
    function deleteMembers(memberId) {
        if (confirm('Are you sure you want to delete this member?')) {
            var xhr = new XMLHttpRequest();
            var url = 'delete_member.php?memberId=' + memberId;
            xhr.open("GET", url, false);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert('Member deleted successfully');
                    location.reload(); // Reload the page to update the table
                }
            };
            xhr.send();
        }

    }

    function updateProduct(productId) {
        if (confirm('Are you sure you want to update this product?')) {
            // Get the form values
            const priceForm = document.getElementById(`updatePriceForm_${productId}`);
            const stockForm = document.getElementById(`updateStockForm_${productId}`);
            const price = priceForm.elements.price.value;
            const stock = stockForm.elements.stock.value;

            // Construct the URL with the data as query parameters
            const url =
                `update_product.php?productId=${productId}&price=${encodeURIComponent(price)}&stock=${encodeURIComponent(stock)}`;

            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);

            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // You may want to check for a success message in the response before confirming the update
                    alert('Product updated successfully');
                    location.reload(); // Reload the page to update the table
                }
            };

            xhr.send();
        }
    }
    </script>
</body>

</html>