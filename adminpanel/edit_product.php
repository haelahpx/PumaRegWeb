<?php
include '../config.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid product ID.'); window.location.href='product.php';</script>";
    exit();
}

$product_id = $_GET['id'];

$productResult = $conn->query("SELECT * FROM product WHERE product_id = $product_id");

if ($productResult->num_rows == 0) {
    echo "<script>alert('Product not found.'); window.location.href='product.php';</script>";
    exit();
}

$product = $productResult->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productname = $_POST['productname'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $uploadDir = '../images/';
    $image = $_FILES['image'];

    if ($image['error'] === UPLOAD_ERR_OK) {
        if ($image['size'] > 2 * 1024 * 1024) {
            echo "<script>alert('File size exceeds 2MB. Please upload a smaller file.');</script>";
        } else {
            $imagePath = uniqid() . '-' . basename($image['name']);
            $targetFilePath = $uploadDir . $imagePath;

            if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
                $conn->query("UPDATE product SET productname = '$productname', category_id = '$category_id', image = '$imagePath', description = '$description', price = '$price' WHERE product_id = $product_id");
            } else {
                echo "<script>alert('Error uploading file.');</script>";
            }
        }
    } else {
        $conn->query("UPDATE product SET productname = '$productname', category_id = '$category_id', description = '$description', price = '$price' WHERE product_id = $product_id");
    }

    echo "<script>alert('Product updated successfully.'); window.location.href='product.php';</script>";
}

$categoryQuery = "SELECT * FROM category";
$categoryResult = $conn->query($categoryQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Product</title>
</head>

<body>
<header class="bg-white shadow-md">
        <nav class="flex justify-between items-center p-4">
            <img class="w-24 cursor-pointer" src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="Logo">
            <div class="nav-links">
                <ul class="flex space-x-6">
                    <li><a class="hover:text-gray-500" href="index.php">Dashboard</a></li>
                    <li><a class="hover:text-gray-500" href="product.php">Product</a></li>
                    <li><a class="hover:text-gray-500" href="category.php">Category</a></li>
                    <li><a href="logout.php" class="text-sm text-black border border-sky-500 rounded-lg p-2 hover:bg-sky-500 hover:text-white">Sign out</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Edit Product</h1>
        <form action="" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="productname">Product Name</label>
                <input type="text" name="productname" id="productname" value="<?php echo htmlspecialchars($product['productname']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Product Name">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">Category</label>
                <select name="category_id" id="category_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Category</option>
                    <?php if ($categoryResult->num_rows > 0): ?>
                        <?php while ($category = $categoryResult->fetch_assoc()): ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo $category['category_id'] == $product['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['categoryname']); ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No categories found</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">Image Upload (Leave blank to keep the current image)</label>
                <input type="file" name="image" id="image" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <?php if ($product['image']): ?>
                    <img src="../images/<?php echo $product['image']; ?>" alt="Current Image" class="mt-2 w-32 h-32 object-cover">
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($product['description']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Description">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price</label>
                <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Price">
            </div>
            <div class="flex items-center justify-between mb-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Product
                </button>
                <a href="product.php" class="text-blue-500 hover:underline">Go Back</a>
            </div>

        </form>
    </main>
</body>

</html>