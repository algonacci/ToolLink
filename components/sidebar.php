<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="index.php">
                    Home
                </a>
                <a class="nav-link" href="borrow_tool.php">
                    Peminjaman Alat
                </a>
                <a class="nav-link" href="return_tool.php">
                    Pengembalian Alat
                </a>
                <a class="nav-link" href="tools_list.php">
                    Alat Terdaftar
                </a>
                <a class="nav-link" href="generate_qr.php">
                    Generate QR
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php echo $_SESSION['username']; ?>
        </div>
    </nav>
</div>