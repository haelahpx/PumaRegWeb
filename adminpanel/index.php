<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $query = "DELETE FROM users WHERE user_id = $user_id";
    $conn->query($query);
}

$query = "SELECT * FROM users";
$result = $conn->query($query);
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
    <main class="p-10">
        <h1 class="text-3xl font-bold mb-6 text-center">User Dashboard</h1>
        <div class="overflow-hidden rounded-lg shadow-lg">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-3 px-4 text-left border-b">#</th>
                        <th class="py-3 px-4 text-left border-b">Username</th>
                        <th class="py-3 px-4 text-left border-b">Email</th>
                        <th class="py-3 px-4 text-left border-b">User Type</th>
                        <th class="py-3 px-4 text-left border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-100 transition duration-300">
                                <td class="py-2 px-4 border-b"><?php echo $row['user_id']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['username']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['email']; ?></td>
                                <td class="py-2 px-4 border-b"><?php echo $row['user_type']; ?></td>
                                <td class="py-2 px-4 border-b">
                                    <form action="" method="POST" class="inline">
                                        <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-2 px-4 text-center">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>