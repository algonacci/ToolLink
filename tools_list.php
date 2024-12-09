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
                    <h1 class="mt-4 mb-5">Daftar Alat</h1>
                    <p>Daftar alat yang tersedia dapat dilihat di bawah ini</p>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Daftar Alat
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alat</th>
                                        <th>Part Number</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tools";
                                    $result = mysqli_query($koneksi, $query);
                                    $no = 1;
                                    
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $status = ($row['is_borrowed'] == 0) ? 'Tersedia' : 'Sedang Dipinjam';
                                        $statusClass = ($row['is_borrowed'] == 0) ? 'text-success' : 'text-danger';
                                        
                                        echo "<tr>";
                                        echo "<td>" . $no++ . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['part_number'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td class='$statusClass'>" . $status . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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
            $('#datatablesSimple').DataTable();
        });
    </script>
</body>

</html>