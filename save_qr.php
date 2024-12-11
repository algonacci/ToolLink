<?php
include 'connection.php';

// Pastikan data 'data' terkirim dari Arduino
if (isset($_POST["data"])) {
    // Memisahkan data berdasarkan koma
    $data = explode(",", $_POST["data"]);

    // Pastikan jumlah elemen yang diterima sesuai dengan yang diharapkan
    if (count($data) != 3) {
        echo "Error: Data format tidak sesuai.";
        exit;
    }

    // Mengambil nilai dari array yang dipisahkan
    $name = trim($data[0]);
    $part_number = trim($data[1]);
    $description = trim($data[2]);

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
} else {
    echo "Error: Data 'data' tidak diterima.";
}

mysqli_close($conn);
