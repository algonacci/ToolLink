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
                    <h1 class="mt-4 mb-5">Pengembalian Alat</h1>
                    <p>Pengembalian alat dapat dilakukan dengan mengisi form pengembalian alat di bawah ini</p>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Alat yang Sedang Dipinjam</h5>
                            <div id="alertContainer"></div>
                            <table id="borrowedToolsTable" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Alat</th>
                                        <th>Nama Peminjam</th>
                                        <th>Part Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT t.*, l.borrower_name 
                                              FROM tools t 
                                              LEFT JOIN (
                                                  SELECT tool_id, borrower_name
                                                  FROM logs 
                                                  WHERE id IN (
                                                      SELECT MAX(id) 
                                                      FROM logs 
                                                      GROUP BY tool_id
                                                  )
                                              ) l ON t.id = l.tool_id 
                                              WHERE t.is_borrowed = 1";
                                    $result = $koneksi->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . ($row['borrower_name'] ?? 'Unknown') . "</td>
                                            <td>" . $row['part_number'] . "</td>
                                            <td><span class='badge bg-warning text-dark'>Dipinjam</span></td>
                                            <td>
                                                <button class='btn btn-success return-tool' 
                                                        data-tool-id='" . $row['id'] . "'>
                                                    Konfirmasi Pengembalian
                                                </button>
                                            </td>
                                        </tr>";
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
        $(document).ready(function () {
            $('#borrowedToolsTable').DataTable();
            
            function showAlert(message, type) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('#alertContainer').html(alertHtml);
                
                // Auto-dismiss after 3 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            $('.return-tool').click(function () {
                const toolId = $(this).data('tool-id');

                if (confirm('Apakah Anda yakin ingin mengkonfirmasi pengembalian alat ini?')) {
                    $.ajax({
                        url: 'process_return.php',
                        method: 'POST',
                        data: { tool_id: toolId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                showAlert('Alat berhasil dikembalikan', 'success');
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            } else {
                                showAlert('Terjadi kesalahan: ' + response.message, 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            showAlert('Terjadi kesalahan pada sistem', 'danger');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>