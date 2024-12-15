<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

include 'database.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>User Dashboard</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Available Attractions</h2>
            <?php
        $query = "SELECT * FROM attractions";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='attraction'>";
                echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' class='attraction-image'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>" . $row['description'] . "</p>";
                echo "<a href='buy_ticket.php?id=" . $row['id'] . "' class='button'>Buy Ticket</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No attractions available.</p>";
        }
        
            ?>
        </section>
    </main>
</body>
</html>
