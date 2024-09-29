<?php
session_start();
include "config.php";

$loggedIn = isset($_SESSION['username']);
$categoryQuery = "SELECT * FROM category";
$categories = $conn->query($categoryQuery);
$selectedCategory = isset($_POST['category_id']) ? $_POST['category_id'] : 'none';
$productQuery = "SELECT * FROM product";
if ($selectedCategory !== 'none') {
    $productQuery .= " WHERE category_id = " . intval($selectedCategory);
}
$result = $conn->query($productQuery);
$allProductsQuery = "SELECT * FROM product";
$allProductsResult = $conn->query($allProductsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</head>

<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }

    .card {
        overflow: hidden;
    }

    .card img {
        transition: transform 0.3s ease;
        /* Smooth scaling */
    }

    .card:hover img {
        transform: scale(1.1);
        /* Scale up on hover */
    }
</style>

<body class="bg-gray-50 overflow-x-hidden">
        <nav class="flex justify-between items-center bg-white shadow-lg w-full h-24 px-6 sticky top-0 z-10">
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

    <section>
        <img src="images/cool, simple& sheesh..png" alt="banner">
    </section>
    <main class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-center mb-6 bg-gradient-to-r from-sky-500 to-sky-400 text-white p-4 rounded-lg shadow-lg">President University Merch</h1>

    <h2 class="text-3xl font-semibold mt-8 text-center text-sky-700 shadow-md rounded-lg p-2">All Items</h2>
    <div class="relative mt-4 px-4 md:px-0">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php while ($allRow = $allProductsResult->fetch_assoc()) { ?>
                    <div class="swiper-slide">
                        <div class="card bg-white rounded-lg shadow-md overflow-hidden">
                            <img class="w-full h-48 object-cover" src="images/<?php echo $allRow['image']; ?>" alt="<?php echo $allRow['productname']; ?>">
                            <div class="p-4 flex flex-col h-56">
                                <h2 class="text-lg font-semibold flex-grow truncate"><?php echo $allRow['productname']; ?></h2>
                                <p class="text-gray-500 mb-2"><?php echo $allRow['description']; ?></p>
                                <p class="text-red-500 mb-2"><?php echo number_format($allRow['price'], 2); ?></p>
                                <a href="buy.php?id=<?php echo $allRow['product_id']; ?>" class="mt-auto text-center text-white bg-sky-500 rounded-lg p-2">Buy</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="absolute top-1/2 left-0 transform -translate-y-1/2 z-10">
            <div class="swiper-button-prev text-gray-700 bg-white rounded-full p-2 hover:bg-gray-200 transition-colors">
                &#9664;
            </div>
        </div>
        <div class="absolute top-1/2 right-0 transform -translate-y-1/2 z-10">
            <div class="swiper-button-next text-gray-700 bg-white rounded-full p-2 hover:bg-gray-200 transition-colors">
                &#9654;
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4 pt-6 text-sky-700">Filtered Items</h2>
    <form method="POST" class="mb-4">
        <select name="category_id" onchange="this.form.submit()" class="border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-500">
            <option value="none" <?php echo ($selectedCategory == 'none') ? 'selected' : ''; ?>>None</option>
            <?php while ($category = $categories->fetch_assoc()) { ?>
                <option value="<?php echo $category['category_id']; ?>" <?php echo ($selectedCategory == $category['category_id']) ? 'selected' : ''; ?>>
                    <?php echo $category['categoryname']; ?>
                </option>
            <?php } ?>
        </select>
    </form>

    <section class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="card bg-white rounded-lg shadow-md overflow-hidden transition-transform transform">
                <img class="w-full h-48 object-cover" src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['productname']; ?>">
                <div class="p-4">
                    <h2 class="text-lg font-semibold"><?php echo $row['productname']; ?></h2>
                    <p class="text-red-500">Rp.<?php echo number_format($row['price'], 2); ?></p>
                    <a href="buy.php?id=<?php echo $row['product_id']; ?>" class="block mt-4 text-center text-white bg-sky-500 rounded-lg p-2 hover:bg-sky-600 transition-colors">Buy</a>
                </div>
            </div>
        <?php } ?>
    </section>
</main>


    <footer class="bg-gray-100 py-6">
        <div class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center space-x-2">
                <img src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="Logo" class="h-8 w-18">
                <span class="font-semibold text-gray-700">PUMA</span>
            </div>
            <p class="text-gray-600">&copy; 2024 Haikal. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 4,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                500: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                769: {
                    slidesPerView: 4,
                }
            }
        });
    </script>
</body>

</html>