<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<title>Watch Store</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(135deg,#9e62ab,#ad6e6e);
font-family:Segoe UI;
min-height:100vh;
color:white;
}

/* navbar */

.navbar{
background:rgba(0,0,0,0.3);
backdrop-filter:blur(10px);
}

/* hero section */

.hero{
height:80vh;
display:flex;
align-items:center;
justify-content:center;
text-align:center;
}

.hero h1{
font-size:50px;
font-weight:bold;
}

.hero p{
font-size:20px;
opacity:0.9;
}

.btn-shop{
text-decoration: none;
margin-top:20px;
display:inline-block;
background:#864383;
color:white;
padding:12px 30px;
border-radius:8px;
transition:0.3s;
}

.btn-shop:hover{
background:#ad6e6e;
transform:scale(1.05);
}


</style>

</head>

<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand text-white fw-bold" href="#">WatchStore</a>

<div>

<?php if(isset($_SESSION['user_id'])){ ?>

<span class="me-3">Welcome <?php echo $_SESSION['user_name']; ?></span>

<a href="logout.php" class="btn btn-light btn-sm">Logout</a>

<?php } else { ?>

<a href="login.php" class="btn btn-light btn-sm me-2">Login</a>
<a href="register.php" class="btn btn-outline-light btn-sm">Register</a>

<?php } ?>

</div>

</div>

</nav>

<!-- Hero -->

<div class="hero">

<div>

<h1>Welcome to My Watch Store</h1>

<p>Discover the best luxury watches</p>

<a href="product.php" class="btn-shop">
Shop Now
</a>


</div>

</div>

</body>
</html>
