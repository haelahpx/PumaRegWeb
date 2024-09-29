<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration successful!')</script>";
        header("Location: login.php");
    } else {
        echo "<script>alert('Registration failed: " . mysqli_error($conn) . "')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <title>Register</title>
</head>
<style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
</style>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <div class="flex gap-24 items-center">
            <div class="text-left">
            <h2 class="text-2xl font-bold text-gray-800">Register</h2>
                <h3 class="text-sm text-gray-800">PresidentUniversity </h3>
            </div>
            <img class="w-24 cursor-pointer" src="https://computing.president.ac.id/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Flogo.554235d9.png&w=256&q=75" alt="...">
        </div>
        <form method="POST" action="">
            <div class="mb-4">
                <input type="text" id="username" name="username" required class="mt-1 block w-full p-2 border border-gray-300 rounded" placeholder="Enter your username">
            </div>
            <div class="mb-4">
                <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded" placeholder="Enter your email">
            </div>
            <div class="mb-4">
                <input type="password" id="password" name="password" required class="mt-1 block w-full p-2 border border-gray-300 rounded" placeholder="Enter your password">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Register</button>
        </form>
        <p class="mt-4 text-center">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>
</body>

</html>