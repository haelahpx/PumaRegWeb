<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['categoryname'])) {
        $categoryname = $_POST['categoryname'];

        if (!empty($categoryname)) {
            $check_query = "SELECT * FROM category WHERE categoryname = '$categoryname'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<script>alert('Category already exists.')</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $query = "INSERT INTO category (categoryname) VALUES ('$categoryname')";
                if (mysqli_query($conn, $query)) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "<script>alert('Failed: " . mysqli_error($conn) . "')</script>";
                }
            }
        }
    }

    if (isset($_POST['edit_id'])) {
        $edit_id = $_POST['edit_id'];
        $updated_name = $_POST['updated_name'];

        if (!empty($updated_name)) {
            $check_query = "SELECT * FROM category WHERE categoryname = '$updated_name' AND category_id != $edit_id";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<script>alert('Category already exists.')</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $update_query = "UPDATE category SET categoryname = '$updated_name' WHERE category_id = $edit_id";
                if (mysqli_query($conn, $update_query)) {
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "<script>alert('Failed to update: " . mysqli_error($conn) . "')</script>";
                }
            }
        }
    }
}

$categories = mysqli_query($conn, "SELECT * FROM category");

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_products_query = "DELETE FROM product WHERE category_id = $delete_id";
    mysqli_query($conn, $delete_products_query);

    $delete_category_query = "DELETE FROM category WHERE category_id = $delete_id";
    mysqli_query($conn, $delete_category_query);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Home - Admin</title>
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

    <main class="container mx-auto my-5 p-6 bg-white rounded-lg text-center">
        <h2 class="text-2xl font-bold text-gray-800">Category Management</h2>
        <form method="POST" class="mt-4">
            <input type="text" id="categoryname" name="categoryname" required class="block w-full p-2 border border-gray-300 rounded mb-4" placeholder="Enter your category name">
            <button type="submit" class="w-full p-2 rounded border text-black border-sky-500 rounded-lg p-2 hover:bg-sky-500 hover:text-white ">Add Category</button>
        </form>

        <table class="min-w-full mt-5 bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">#</th>
                    <th class="py-2 px-4 border-b">Category Name</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b"><?= $row['category_id'] ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['categoryname']) ?></td>
                        <td class="py-2 px-4 border-b">
                            <form method="POST" class="inline">
                                <input type="hidden" name="edit_id" value="<?= $row['category_id'] ?>">
                                <input type="text" name="updated_name" value="<?= htmlspecialchars($row['categoryname']) ?>" class="border border-gray-300 rounded p-1" required>
                                <button type="submit" class="text-sky-500 hover:text-sky-700 ml-2">Update</button>
                            </form>
                            <a href="?delete_id=<?= $row['category_id'] ?>" class="ml-4 p-1 text-white border border-orange-600 rounded-md bg-orange-600 hover:bg-orange-700">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>

</html>
