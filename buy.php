<?php
session_start();
include "config.php";

$loggedIn = isset($_SESSION['username']);

$product_id = null;
$product_name = '';
$price = 0;
$description = '';
$image = '';
$message = '';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $query = "SELECT * FROM product WHERE product_id = $product_id";
    $result = $conn->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        $product_name = htmlspecialchars($row['productname']);
        $price = $row['price'];
        $description = htmlspecialchars($row['description']);
        $image = htmlspecialchars($row['image']);
    } else {
        $message = "Product not found.";
    }
} else {
    $message = "No product selected.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    if (!$loggedIn) {
        echo "<script>
                alert('You have to log in to purchase a product.');
                window.location.href = 'index.php';
            </script>";
        exit;
    }

    $purchased_product_id = $_POST['product_id'];
    $purchased_product_name = $_POST['product_name'];
    $purchased_price = $_POST['price'];
    $quantity = intval($_POST['quantity']);
    $total_price = $purchased_price * $quantity;

    $_SESSION['purchased_product'] = [
        'id' => $purchased_product_id,
        'name' => $purchased_product_name,
        'price' => $total_price,
        'quantity' => $quantity
    ];

    $message = "Thank you for purchasing: <strong>" . htmlspecialchars($purchased_product_name) . "</strong> x" . $quantity . " for Rp." . number_format($total_price, 2) . "!";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
</style>

<body>
<header class="bg-white shadow-lg">
        <nav class="flex justify-between items-center w-full h-24 px-6">
            <div class="flex items-center">
                <img class="w-20 md:w-24 cursor-pointer" src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="...">
                <div class="hidden lg:block ml-4">
                    <p class="font-bold text-base">President University</p>
                    <p class="font-normal text-sm">Puma Merch</p>
                </div>
            </div>
            <div class="nav-links">
                <ul class="flex items-center space-x-6 text-sm">
                    <li>
                        <a class="hover:text-sky-500 transition-colors" href="index.php">Home</a>
                    </li>
                    <?php if ($loggedIn) { ?>
                        <li>
                            <a href="adminpanel/logout.php" class="text-black hover:text-white border border-sky-500 rounded-lg px-4 py-2 transition-colors hover:bg-sky-500">Sign out</a>
                        </li>
                    <?php } else { ?>
                        <div class="flex space-x-2">
                            <a href="login.php" class="text-black hover:text-white rounded-lg px-4 py-2 transition-colors hover:bg-sky-500">Log In</a>
                            <a href="register.php" class="text-black hover:text-white border border-sky-500 rounded-lg px-4 py-2 transition-colors hover:bg-sky-500">Sign Up</a>
                        </div>
                    <?php } ?>
                </ul>
            </div>
        </nav>
    </header>
    <div class="max-w-lg mx-auto mt-10 bg-white rounded-lg shadow-md overflow-hidden">
        <?php if ($image): ?>
            <img class="w-full h-48 object-cover" src="images/<?php echo $image; ?>" alt="<?php echo $product_name; ?>">
        <?php endif; ?>
        <div class="p-4">
            <h2 class="text-2xl font-semibold"><?php echo $product_name; ?></h2>
            <p class="text-gray-500 text-lg">Rp. <?php echo number_format($price, 2); ?></p>
            <p class="text-gray-700 mt-2"><?php echo $description; ?></p>

            <form method="post" action="" class="mt-4">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <input type="hidden" name="product_name" value="<?php echo $product_name; ?>">
                <input type="hidden" name="price" value="<?php echo $price; ?>">

                <label for="quantity" class="block text-gray-700">Quantity:</label>
                <input type="number" name="quantity" id="quantity" min="1" value="1" class="mt-1 p-2 border border-gray-300 rounded w-full">

                <div class="flex flex-col items-center">
                    <button type="submit" class="w-full text-center text-white bg-sky-500 rounded-lg p-2 hover:bg-sky-600 mt-4">
                        Buy
                    </button>
                    <a href="index.php" class="text-blue-500 hover:underline mt-2">
                        Go Back
                    </a>
                </div>

            </form>

            <?php if ($message): ?>
                <p class='text-center text-green-500 mt-4'><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!$image): ?>
        <p class='text-center text-red-500 mt-4'><?php echo $message; ?></p>
    <?php endif; ?>

</body>

</html>