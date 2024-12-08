<?php
include 'connection.php';
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
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.html">
                            Home
                        </a>
                        <a class="nav-link" href="index.html">
                            Peminjaman Alat
                        </a>
                        <a class="nav-link" href="index.html">
                            Pengembalian Alat
                        </a>
                        <a class="nav-link" href="index.html">
                            Alat Terdaftar
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Admin
                </div>
            </nav>
        </div>
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
                                            <tr>
                                                <td>Asep</td>
                                            </tr>
                                            <tr>
                                                <td>Sonny</td>
                                            </tr>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Hammer</td>
                                                <td>HT</td>
                                            </tr>
                                            <tr>
                                                <td>PC001</td>
                                                <td>PC002</td>
                                            </tr>
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
                                            <th>Tanggal Pengembalian</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>PM001</td>
                                            <td>Asep</td>
                                            <td>Hammer</td>
                                            <td>HT001</td>
                                            <td>2023-12-01</td>
                                            <td>2023-12-05</td>
                                            <td>Dikembalikan</td>
                                        </tr>
                                        <tr>
                                            <td>PM002</td>
                                            <td>Sonny</td>
                                            <td>Screwdriver</td>
                                            <td>SD002</td>
                                            <td>2023-12-03</td>
                                            <td>2023-12-07</td>
                                            <td>Dikembalikan</td>
                                        </tr>
                                        <tr>
                                            <td>PM003</td>
                                            <td>Andi</td>
                                            <td>Drill</td>
                                            <td>DR003</td>
                                            <td>2023-12-04</td>
                                            <td>2023-12-10</td>
                                            <td>Belum Dikembalikan</td>
                                        </tr>
                                        <tr>
                                            <td>PM004</td>
                                            <td>Budi</td>
                                            <td>Wrench</td>
                                            <td>WR004</td>
                                            <td>2023-12-05</td>
                                            <td>2023-12-09</td>
                                            <td>Dikembalikan</td>
                                        </tr>
                                        <tr>
                                            <td>PM005</td>
                                            <td>Citra</td>
                                            <td>Multimeter</td>
                                            <td>MM005</td>
                                            <td>2023-12-06</td>
                                            <td>2023-12-12</td>
                                            <td>Belum Dikembalikan</td>
                                        </tr>
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
            $('#logTable').DataTable();
        });
    </script>
</body>

</html>