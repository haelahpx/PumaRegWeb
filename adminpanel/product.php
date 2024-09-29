<?php
include '../config.php';

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
                $stmt = $conn->prepare("INSERT INTO product (productname, category_id, image, description, price) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sisss", $productname, $category_id, $imagePath, $description, $price);

                if ($stmt->execute()) {
                    echo "<script>alert('New record created successfully');</script>";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Error uploading file.');</script>";
            }
        }
    } else {
        echo "<script>alert('No file uploaded or there was an upload error.');</script>";
    }
}

if (isset($_GET['delete_id'])) {
    $product_id = intval($_GET['delete_id']);
    $query = "SELECT image FROM product WHERE product_id = $product_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];
        $deleteQuery = "DELETE FROM product WHERE product_id = $product_id";

        if ($conn->query($deleteQuery) === TRUE) {
            if ($image) {
                unlink('../images/' . $image);
            }
            header("Location: " . $_SERVER['PHP_SELF'] . "?message=Product deleted successfully");
            exit();
        } else {
            echo "<script>alert('Error deleting product: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('No product found with that ID.');</script>";
    }
}

$query = "SELECT p.*, c.categoryname FROM product p LEFT JOIN category c ON p.category_id = c.category_id";
$result = $conn->query($query);

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
    <title>Product Management</title>
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
    <main class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-md">
        <?php if (isset($_GET['message'])): ?>
            <div class="bg-green-500 text-white p-3 mb-4 rounded">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-center mb-4">Add Product</h1>
        <form action="" method="post" enctype="multipart/form-data" class="space-y-4">
            <div>
                <input type="text" name="productname" id="productname" required class="border rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Product Name">
            </div>
            <div>
                <select name="category_id" id="category_id" required class="border rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Category</option>
                    <?php if ($categoryResult->num_rows > 0): ?>
                        <?php while ($category = $categoryResult->fetch_assoc()): ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['categoryname']); ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">No categories found</option>
                    <?php endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-600" for="image">Image Upload</label>
                <input type="file" name="image" id="image" accept="image/*" required class="border rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <input type="text" name="description" id="description" required class="border rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description">
            </div>
            <div>
                <input type="text" name="price" id="price" class="border rounded-lg w-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Price">
            </div>
            <div>
                <button type="submit" class="w-full p-2 rounded border text-black border-sky-500 hover:bg-sky-500 hover:text-white">Add Product</button>
            </div>
        </form>

        <h2 class="text-xl font-bold mb-4 text-center mt-8">Product List</h2>
        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-4">Image</th>
                    <th class="border p-4">Product Name</th>
                    <th class="border p-4">Category Name</th>
                    <th class="border p-4">Description</th>
                    <th class="border p-4">Price</th>
                    <th class="border p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <td class="border p-4"><img src="../images/<?php echo $row['image']; ?>" alt="<?php echo $row['productname']; ?>" class="w-16 h-16 object-cover"></td>
                            <td class="border p-4"><?php echo htmlspecialchars($row['productname']); ?></td>
                            <td class="border p-4"><?php echo htmlspecialchars($row['categoryname']); ?></td>
                            <td class="border p-4"><?php echo htmlspecialchars($row['description']); ?></td>
                            <td class="border p-4"><?php echo htmlspecialchars($row['price']); ?></td>
                            <td class="border p-4">
                                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                                <a href="?delete_id=<?php echo $row['product_id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="border p-4 text-center">No products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>

</html>