<?php
session_start();

// Cek apakah user sudah login dan memiliki role admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'database.php';

// Proses jika form untuk menambah atraksi dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Tentukan folder tempat file gambar akan disimpan
    $image_path = "" . basename($image);

    // Pindahkan file gambar ke folder uploads
    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        // Query untuk menambah atraksi baru
        $query = "INSERT INTO attractions (name, description, image) VALUES ('$name', '$description', '$image')";
        if ($conn->query($query)) {
            header("Location: admin_dashboard.php"); // Redirect setelah berhasil
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}

// Periksa apakah ada permintaan untuk mengedit
$edit_mode = false;
$edit_data = null;

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit_id'];

    // Ambil data berdasarkan ID
    $edit_query = "SELECT * FROM attractions WHERE id = $edit_id";
    $edit_result = $conn->query($edit_query);

    if ($edit_result->num_rows > 0) {
        $edit_data = $edit_result->fetch_assoc();
    } else {
        echo "Error: Data not found.";
    }
}

// Proses Update Data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    // Tentukan folder gambar baru (jika ada)
    if (!empty($image)) {
        $image_path = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        // Query untuk update dengan gambar baru
        $update_query = "UPDATE attractions SET name='$name', description='$description', image='$image' WHERE id=$id";
    } else {
        // Query untuk update tanpa mengganti gambar
        $update_query = "UPDATE attractions SET name='$name', description='$description' WHERE id=$id";
    }

    if ($conn->query($update_query)) {
        header("Location: admin_dashboard.php"); // Redirect setelah update berhasil
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link ke file CSS -->
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
    <section>
    <h2><?php echo $edit_mode ? "Edit Attraction" : "Add New Attraction"; ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $edit_mode ? $edit_data['id'] : ''; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $edit_mode ? $edit_data['name'] : ''; ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $edit_mode ? $edit_data['description'] : ''; ?></textarea><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br>
        <?php if ($edit_mode && !empty($edit_data['image'])): ?>
            <img src="<?php echo $edit_data['image']; ?>" alt="Current Image" width="100"><br>
        <?php endif; ?>

        <button type="submit" name="<?php echo $edit_mode ? "update" : "submit"; ?>">
            <?php echo $edit_mode ? "Update Attraction" : "Add Attraction"; ?>
        </button>
    </form>
</section>


        <section>
            <h2>Existing Attractions</h2>
            <?php
            // Query untuk menampilkan atraksi yang ada
            $query = "SELECT * FROM attractions";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='attraction'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "' width='100'><br>";
                    echo "<div class='button-group'>";
                    echo "<a href='admin_dashboard.php?edit_id=" . $row['id'] . "' class='button'>Edit</a>";
                    echo "<form method='POST' action='delete.php' onsubmit='return confirm(\"Are you sure you want to delete this attraction?\")' style='display:inline;'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' class='delete-button'>Delete</button>";
                    echo "</form>";

                    echo "</div>";
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
