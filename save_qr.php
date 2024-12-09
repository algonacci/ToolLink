<?php
include 'connection.php';

if (isset($_POST["name"]) && isset($_POST["part_number"]) && isset($_POST["description"])) {

    $name = $_POST["name"];
    $part_number = $_POST["part_number"];
    $description = $_POST["description"];

    // Menggunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO tools (name, part_number, description, is_borrowed) 
                            VALUES (?, ?, ?, 0)");
    $stmt->bind_param("sss", $name, $part_number, $description);

    if ($stmt->execute()) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

mysqli_close($conn);
?>