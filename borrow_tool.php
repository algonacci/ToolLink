<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
include "includes/head.php"
    ?>

<body class="sb-nav-fixed">
    <?php
    include "components/navbar.php";
    ?>
    <div id="layoutSidenav">
        <?php
        include "components/sidebar.php";
        ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-5">Peminjaman Alat</h1>
                    <p>Peminjaman alat dapat dilakukan dengan mengisi form peminjaman alat di bawah ini</p>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Form Peminjaman Alat</h5>
                            <?php
                            if (isset($_POST['submit'])) {
                                $borrower_name = $_POST['borrower_name'];
                                $tool_id = $_POST['tool_id'];
                                $aircraft_reg = $_POST['aircraft_reg'];
                                $calibration = $_POST['calibration'];

                                // Query to insert data into the logs table
                                $query = "INSERT INTO logs (borrower_name, tool_id, timestamp, calibration, aircraft_reg, status) VALUES (?, ?, NOW(), ?, ?, 'Dipinjam')";
                                $stmt = $koneksi->prepare($query);
                                $stmt->bind_param("siss", $borrower_name, $tool_id, $calibration, $aircraft_reg);
                                $stmt->execute();

                                // Query to update the tools table
                                $update_query = "UPDATE tools SET is_borrowed = TRUE WHERE id = ?";
                                $update_stmt = $koneksi->prepare($update_query);
                                $update_stmt->bind_param("i", $tool_id);
                                $update_stmt->execute();

                                if ($stmt->affected_rows > 0 && $update_stmt->affected_rows > 0) {
                                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            Peminjaman alat berhasil. Alat akan segera dipinjamkan.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                          </div>';
                                } else {
                                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            Gagal meminjam alat. Silakan coba lagi.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                          </div>';
                                }
                            }
                            ?>
                            <form action="borrow_tool.php" method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="borrower_name" class="form-label">Nama Peminjam</label>
                                            <input type="text" class="form-control" id="borrower_name"
                                                name="borrower_name" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="tool_id" class="form-label">Pilih Alat</label>
                                            <select class="form-select" id="tool_id" name="tool_id" required>
                                                <option value="">Pilih alat...</option>
                                                <?php
                                                $query = "SELECT * FROM tools WHERE is_borrowed = 0";
                                                $result = $koneksi->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . " - " . $row['part_number'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="aircraft_reg" class="form-label">Aircraft Registration</label>
                                            <select class="form-select" id="aircraft_reg" name="aircraft_reg" required>
                                                <option value="">Pilih registrasi pesawat...</option>
                                                <option value="PK-GFS">PK-GFS</option>
                                                <option value="PK-GFU">PK-GFU</option>
                                                <option value="PK-GFV">PK-GFV</option>
                                                <option value="PK-GFW">PK-GFW</option>
                                                <option value="PK-GFX">PK-GFX</option>
                                                <option value="PK-GUA">PK-GUA</option>
                                                <option value="PK-GUC">PK-GUC</option>
                                                <option value="PK-GUD">PK-GUD</option>
                                                <option value="PK-GUE">PK-GUE</option>
                                                <option value="PK-GUF">PK-GUF</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="calibration" class="form-label">Calibration</label>
                                            <input type="text" class="form-control" id="calibration" name="calibration"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" name="submit" class="btn btn-primary">Pinjam Alat</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <?php
            include "components/footer.php";
            ?>
        </div>
    </div>
    <?php
    include "includes/script.php";
    ?>
</body>

</html>