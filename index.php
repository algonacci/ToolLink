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
                    <h1 class="mt-4 mb-5">Selamat Datang di ToolLink</h1>
                    <div class="row">
                        <div class="col">
                            <h2>
                                Peminjam
                            </h2>
                            <div class="card shadow-sm">
                                <div class="card-header text-center">
                                    <h5 class="mb-0">Data Nama Peminjam</h5>
                                </div>
                                <div class="card-body">
                                    <table id="borrowersTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT DISTINCT borrower_name FROM logs";
                                            $result = $koneksi->query($query);
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr><td>" . $row['borrower_name'] . "</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-4">
                            <h2>
                                Alat Terdaftar
                            </h2>
                            <div class="card shadow-sm">
                                <div class="card-header text-center">
                                    <h5 class="mb-0">Data Alat Terdaftar</h5>
                                </div>
                                <div class="card-body">
                                    <table id="toolsTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Alat</th>
                                                <th>Part Number</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php
                                           $query = "SELECT * FROM tools";
                                           $result = $koneksi->query($query);
                                           while ($row = $result->fetch_assoc()) {
                                                echo "<tr><td>" . $row['name'] . "</td><td>" . $row['part_number'] . "</td><td><span class='badge " . ($row['is_borrowed'] ? 'bg-warning text-dark' : 'bg-success') . "'>" . ($row['is_borrowed'] ? 'Dipinjam' : 'Tersedia') . "</span></td></tr>"      ;
                                           }
                                           ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <h2>
                            Riwayat Peminjaman
                        </h2>
                        <div class="card shadow-sm">
                            <div class="card-header text-center">
                                <h5 class="mb-0">Data Riwayat Peminjaman</h5>
                            </div>
                            <div class="card-body">
                                <table id="logTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nomor Peminjaman</th>
                                            <th>Nama Peminjam</th>
                                            <th>Nama Alat</th>
                                            <th>Part Number</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Kalibrasi</th>
                                            <th>Registrasi Pesawat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        $query = "SELECT l.id, l.borrower_name, t.name as tool_name, 
                                                t.part_number, l.timestamp, l.calibration, 
                                                l.aircraft_reg, t.is_borrowed, l.status 
                                                FROM logs l
                                                JOIN tools t ON l.tool_id = t.id";
                                        $result = $koneksi->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>" . $row['id'] . "</td>
                                                <td>" . $row['borrower_name'] . "</td>
                                                <td>" . $row['tool_name'] . "</td>
                                                <td>" . $row['part_number'] . "</td>
                                                <td>" . $row['timestamp'] . "</td>
                                                <td>" . $row['calibration'] . "</td>
                                                <td>" . $row['aircraft_reg'] . "</td>
                                                    <td><span class='badge " . ($row['status'] == 'Dipinjam' ? "bg-warning text-dark" : "bg-success") . "'>" . 
                                                        $row['status'] . 
                                                    "</span>
                                                </td>
                                            </tr>";
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>
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

    <script>
        $(document).ready(function() {
            $('#borrowersTable').DataTable();
            $('#toolsTable').DataTable();
            $('#logTable').DataTable({
                "order": [[4, "desc"]]
            });
        });
    </script>
</body>

</html>