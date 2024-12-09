<?php
include 'connection.php';
session_start();
?>

<html>
<head>
    <title>Login ToolLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $koneksi->query($query);

        if ($result->num_rows == 1) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            // Add error message for incorrect login
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    showLoginError();
                });
            </script>";
        }
    }
    ?>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <!-- Add error alert div -->
                <div class="alert alert-danger alert-dismissible fade" id="loginError" role="alert" style="display: none;">
                    <strong>Login Failed!</strong> Incorrect username or password.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function showLoginError() {
            const errorAlert = document.getElementById('loginError');
            errorAlert.style.display = 'block';
            errorAlert.classList.add('show');
            
            // Shake animation for the login card
            const card = document.querySelector('.card');
            card.style.animation = 'shake 0.5s';
            
            // Remove animation after it completes
            setTimeout(() => {
                card.style.animation = '';
            }, 500);
        }
    </script>
    
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
</body>
</html>


