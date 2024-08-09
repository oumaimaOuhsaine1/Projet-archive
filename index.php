<?php
session_start();

$servername = "localhost";
$username_db = "root";
$password_db = "";
$database = "archive";

$conn = new mysqli($servername, $username_db, $password_db, $database);

if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role FROM admin WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];
                
                header("Location: admin/index.php");
                exit();
            } else {
                $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
        
        $stmt->close();
    } else {
        $error_message = "Erreur dans la préparation de la requête : " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Se connecter</title>
</head>
<body>
    <div class="box">
        <div class="container">
            <div class="top-header">
                <header>Se connecter</header>
            </div>
            <form action="" method="post">
                <div class="error-container">
                    <?php if ($error_message): ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="input-field">
                    <input type="text" name="username" class="input" placeholder="Username" required>
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-field">
                    <input type="password" name="password" class="input" placeholder="Password" required>
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-field">
                    <input type="submit" class="submit" value="Se connecter">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
