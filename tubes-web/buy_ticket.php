<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

include 'database.php';

// Ambil ID wahana dari parameter URL
$attraction_id = $_GET['id'];

// Query untuk mendapatkan detail wahana berdasarkan ID
$query = "SELECT * FROM attractions WHERE id = '$attraction_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Jika wahana tidak ditemukan
    echo "Attraction not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Ticket - <?php echo $row['name']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Buy Ticket for <?php echo $row['name']; ?></h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section>
            <h2><?php echo $row['name']; ?></h2>
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="attraction-image">
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($row['price'], 2); ?></p>
            <p><strong>Opening Hours:</strong> <?php echo $row['opening_hours']; ?></p>
            <!-- Form to buy ticket (you can add additional functionality here) -->
            <form method="POST" action="purchase_ticket.php">
                <input type="hidden" name="attraction_id" value="<?php echo $row['id']; ?>">
                <a href="purchase_ticket.php?id=<?php echo $row['id']; ?>" class="button">Purchase Ticket</a>
            </form>
        </section>
    </main>
</body>
</html>
