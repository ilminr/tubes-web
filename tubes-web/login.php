<?php
session_start();
include 'database.php';

// Password yang akan di-hash
$password = 'admin123'; 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Query untuk memasukkan pengguna
$query = "INSERT INTO users (username, password, role) VALUES ('admin1', '$hashed_password', 'admin')";
$conn->query($query);

$password = 'user123'; 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (username, password, role) VALUES ('user1', '$hashed_password', 'user')";
$conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Ambil role dari form

    // Gunakan prepared statement untuk mencegah SQL injection
    $query = "SELECT * FROM users WHERE username = ? AND role = ?"; 
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $role); // Mengikat parameter, 'ss' untuk string
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Debugging: Cek hasil query
        var_dump($user); // Menampilkan data yang diambil dari database

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Jika password cocok, buat session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found or role mismatch.";
    }
}
if (isset($_GET['message']) && $_GET['message'] == 'login_required') {
    echo "<p style='color: red;'>You need to log in first to access this page.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Disneyland</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
