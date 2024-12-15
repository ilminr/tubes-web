<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

include 'database.php';

// Cek jika ID wahana ada di URL
if (isset($_GET['id'])) {
    $attraction_id = $_GET['id'];

    // Query untuk mendapatkan detail wahana
    $query = "SELECT * FROM attractions WHERE id = '$attraction_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        // Di sini Anda bisa menambahkan logika untuk mencatat pembelian tiket, misalnya dengan menyimpan di tabel pembelian

        // Tampilkan pesan sukses
        $success_message = "Congratulations! You've successfully purchased a ticket for " . $name . ".";
    } else {
        $error_message = "Attraction not found.";
    }
} else {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Ticket</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Purchase Ticket</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section>
            <?php
            if (isset($success_message)) {
                echo "<div class='success-message'>$success_message</div>";
            } elseif (isset($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            }
            ?>
        </section>
    </main>
</body>
</html>
