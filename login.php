<?php
session_start();
include 'config.php';

$error = "";
    $loggedIn = isset($_SESSION['username']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));

    $sql = "SELECT * FROM users WHERE (username = '$username' OR email = '$username') LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                if ($user['user_type'] === 'admin') {
                    header("Location: adminpanel/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid username/email or password.";
            }
        } else {
            $error = "Invalid username/email or password.";
        }
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pumareg Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen">
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-96 p-6 bg-white border border-gray-200 rounded-lg shadow-md">
        <div class="flex gap-24 items-center">
            <div class="text-left">
                <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                <h3 class="text-sm text-gray-800">President University </h3>
            </div>
            <img class="w-24 cursor-pointer" src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="...">
        </div>
        <?php
        if (!empty($error)) {
            echo "<div class='mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md'>" . $error . "</div>";
        }
        ?>
        <form action="login.php" method="POST" class="mt-6">
            <div class="mb-4">
                <input type="text" id="username" name="username" required aria-label="Username or Email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500" placeholder="Username or Email">
            </div>

            <div class="mb-4">
                <input type="password" id="password" name="password" required aria-label="Password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-500" placeholder="Password">
            </div>

            <button type="submit" name="login" class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-300">Login</button>
            <p class="pt-2 text-center">Don't have an account? <a href="register.php" class="text-blue-400">Sign Up</a>!</p>
            <p class="text-center">Wanna Go Home? <a href="index.php" class="text-blue-400">click here</a>!</p>
        </form>
    </div>
</div>

</body>

</html>
