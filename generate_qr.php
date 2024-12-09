<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
include "includes/head.php";
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
                    <h1 class="mt-4 mb-5">Generate QR</h1>
                    <p>Generate QR dapat dilakukan dengan mengisi form peminjaman alat di bawah ini</p>
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Form Generate QR</h5>

                            <form id="qrForm">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label">Nama Alat</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="part_number" class="form-label">Nomor Part</label>
                                            <input type="text" class="form-control" id="part_number" name="part_number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="description" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Generate QR</button>
                                </div>
                            </form>

                            <!-- Container untuk menampilkan QR Code -->
                            <div id="qrCodeContainer" style="display:none; margin-top: 20px;">
                                <h5 class="mb-3">QR Code</h5>
                                <!-- Tempat untuk menampilkan gambar QR Code -->
                                <img id="qrCodeImage" src="" alt="QR Code" style="max-width: 200px; margin-bottom: 20px;">

                                <!-- Tombol Download -->
                                <a id="downloadBtn" class="btn btn-success mt-3" style="display:none;" download="QRCode.jpg">Download QR Code</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const qrForm = document.getElementById('qrForm');
            const downloadBtn = document.getElementById('downloadBtn');

            qrForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah form refresh

                console.log("Form submitted");

                // Mengambil data dari form
                const name = document.getElementById('name').value;
                const partNumber = document.getElementById('part_number').value;
                const description = document.getElementById('description').value;

                // Pastikan qrCodeText adalah string yang valid
                const qrCodeText = `Name: ${name}\nPart Number: ${partNumber}\nDescription: ${description}`;
                console.log("QR Code Text:", qrCodeText); // Debugging output

                // Menggunakan library QRCode.js untuk menghasilkan QR Code sebagai URL (base64)
                try {
                    QRCode.toDataURL(qrCodeText, { errorCorrectionLevel: 'H', type: 'image/jpeg' }, function (error, url) {
                        if (error) {
                            console.error(error);
                            alert('An error occurred while generating QR code.');
                        } else {
                            console.log("QR Code generated successfully");

                            // Menampilkan QR Code dalam bentuk gambar
                            const qrCodeImage = document.getElementById('qrCodeImage');
                            qrCodeImage.src = url;
                            document.getElementById('qrCodeContainer').style.display = 'block';

                            // Menambahkan URL ke tombol download
                            downloadBtn.href = url;
                            downloadBtn.style.display = 'inline'; // Menampilkan tombol Download
                        }
                    });
                } catch (error) {
                    console.error('Error generating QR Code:', error);
                    alert('An error occurred while generating QR code.');
                }
            });
        });
    </script>
</body>

</html>
