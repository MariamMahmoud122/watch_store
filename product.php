<?php 
include "includes/db.php"; 
include "includes/header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Watch Store | Premium Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            background: linear-gradient(135deg, #9e62ab30, #ad6e6e30); 
            font-family: 'Segoe UI', sans-serif; 
        }
        h2 { font-weight: bold; color: #333; margin-top: 30px; }
        
       
        .search-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            height: 100%;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        .card img {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        .price {
            color: #864383;
            font-weight: bold;
            font-size: 22px;
        }
        .details-btn {
            background: #864383;
            color: white;
            border-radius: 8px;
            padding: 10px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
        }
        .details-btn:hover {
            background: #ad6e6e;
            color: white;
        }
        .add-to-cart-btn {
            border-radius: 8px;
            margin-top: 10px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Latest Watches</h2>

    <div class="search-container">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search for a watch..." value="<?php echo $_GET['search'] ?? ''; ?>">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    <?php
                    $cat_query = mysqli_query($conn, "SELECT * FROM categories");
                    while($c = mysqli_fetch_assoc($cat_query)){
                        $selected = (isset($_GET['category']) && $_GET['category'] == $c['id']) ? 'selected' : '';
                        echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark w-100">Search</button>
            </div>
        </form>
    </div>

    <div class="row">
        <?php
        $where = [];
        if(!empty($_GET['search'])){
            $s = mysqli_real_escape_string($conn, $_GET['search']);
            $where[] = "products.title LIKE '%$s%'";
        }
        if(!empty($_GET['category'])){
            $c = (int)$_GET['category'];
            $where[] = "products.category_id = $c";
        }

        $whereSQL = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

        $query = "SELECT products.*, categories.name AS category_name 
                  FROM products 
                  LEFT JOIN categories ON products.category_id = categories.id 
                  $whereSQL 
                  ORDER BY products.id DESC";

        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
        ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center">
                    <img src="assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="Watch Image">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo $row['title']; ?></h5>
                        <p class="text-muted small"><?php echo $row['category_name']; ?></p>
                        <p class="price mb-3">$<?php echo number_format($row['price'], 2); ?></p>
                        
                        <a href="product_details.php?id=<?php echo $row['id']; ?>" class="details-btn">View Details</a>
                        
                        <button type="button" 
                                class="btn btn-outline-dark add-to-cart-btn" 
                                data-id="<?php echo $row['id']; ?>">
                           <i class="fa-solid fa-cart-shopping fa-lg"></i> Add To Cart
                        </button>
                    </div>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<div class='col-12 text-center'><p class='alert alert-light'>No products found matching your search.</p></div>";
        }
        ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
    btn.addEventListener("click", function() {
        const productId = this.getAttribute("data-id");
        const currentBtn = this;
        const originalContent = currentBtn.innerHTML;

        fetch("add_to_cart.php?id=" + productId)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "login_required") {
                Swal.fire({
                    icon: "info",
                    title: "Login Required",
                    text: "Please login to add items to your cart",
                    confirmButtonText: "Login Now",
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) window.location = "login.php";
                });
            } else {
                
                Swal.fire({
                    icon: "success",
                    title: "Added!",
                    text: "Product successfully added to cart",
                    timer: 1000,
                    showConfirmButton: false
                });

                
                currentBtn.innerHTML = "✔ Added";
                currentBtn.classList.replace("btn-outline-dark", "btn-success");
                currentBtn.disabled = true; //  تعطيل الزرار لحظياً لمنع الضغط المتكرر

                
                const cartCountElement = document.getElementById("cart-count");
                if(cartCountElement) {
                    let currentCount = parseInt(cartCountElement.innerText) || 0;
                    cartCountElement.innerText = currentCount + 1;
                }

                
                setTimeout(() => {
                    currentBtn.innerHTML = originalContent;
                    currentBtn.classList.replace("btn-success", "btn-outline-dark");
                    currentBtn.disabled = false;
                }, 2000);
            }
        });
    });
});

</script>

</body>
</html>