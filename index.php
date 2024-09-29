<?php
session_start();
include "config.php";

$loggedIn = isset($_SESSION['username']);

$query = "SELECT * FROM product"; // Adjust as needed
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
</style>

<body class="bg-white">
<header class="bg-white">
    <nav class="flex justify-between items-center w-full h-16 shadow-md sticky top-0 z-50 bg-white">
        <div class="p-4 pl-6 flex items-center">
            <img class="w-20 md:w-24 cursor-pointer" src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="...">
            <div class="hidden lg:block">
                <p class="pl-4 font-bold text-sm md:text-base">President University</p>
                <p class="pl-4 font-normal text-xs md:text-sm">Puma Merch</p>
            </div>
        </div>
        <div class="nav-links p-4 pr-6">
            <ul class="flex flex-row items-center md:gap-[4vw] gap-4 md:gap-8 text-xs md:text-sm">
                <li>
                    <a class="hover:text-gray-500" href="#">Home</a>
                </li>
                <?php if ($loggedIn) { ?>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="adminpanel/logout.php" class="text-sm text-black hover:text-white border border-2 border-sky-500 rounded-lg p-2 md:p-4 hover:bg-sky-500">Sign out</a>
                        </li>
                    </ul>
                <?php } else { ?>
                    <div class="flex space-x-2 md:space-x-3">
                        <a href="login.php" class="text-sm text-black hover:text-white rounded-lg p-2 md:p-4 hover:bg-sky-500">Log In</a>
                        <a href="register.php" class="text-sm text-black hover:text-white border border-2 border-sky-500 rounded-lg p-2 md:p-4 hover:bg-sky-500">Sign Up</a>
                    </div>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>

    <main class="container mx-auto p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform transform">
                <img class="w-full h-48 object-cover" src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['productname']; ?>">
                <div class="p-4">
                    <h2 class="text-lg font-semibold"><?php echo $row['productname']; ?></h2>
                    <p class="text-gray-500">Rp.<?php echo number_format($row['price'], 2); ?></p>
                    <a href="buy.php?id=<?php echo $row['product_id']; ?>" class="block mt-4 text-center text-white bg-sky-500 rounded-lg p-2 hover:bg-sky-600">Buy</a>
                </div>
            </div>
        <?php } ?>
    </main>

    <footer class="bg-gray-100 py-6">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <img src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="Logo" class="h-8 w-8">
            <span class="font-semibold text-gray-700">PUMA</span>
        </div>

        <nav class="flex space-x-4">
            <a href="index.php" class="text-gray-600 hover:text-gray-900">Home</a>
        </nav>

        <p class="text-gray-600">&copy; 2024 Haikal. All rights reserved.</p>
    </div>
</footer>

</body>

</html>