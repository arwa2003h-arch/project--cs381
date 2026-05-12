<?php
require_once "includes/db.php";

$message = "";
$messageColor = "#dc2626";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST["fullName"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirmPassword = trim($_POST["confirmPassword"] ?? "");

    if ($fullName == "" || $username == "" || $password == "" || $confirmPassword == "") {
        $message = "Please fill in all fields.";
    } elseif (strlen($username) < 5) {
        $message = "Username must be at least 5 characters.";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } elseif ($password != $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute([":username" => $username]);

        if ($stmt->fetch()) {
            $message = "Username already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (full_name, username, password, role) VALUES (:full_name, :username, :password, 'student')");
            $stmt->execute([
                ":full_name" => $fullName,
                ":username" => $username,
                ":password" => $password
            ]);

            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - YIC Course Feedback System</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="site-header">
    <div class="container header-content">
      <div class="brand">
        <h1>YIC Course Feedback System</h1>
        <p>Browse courses and share your feedback</p>
      </div>

      <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php" class="register-btn active">Register</a>
      </nav>
    </div>
  </header>

  <main class="page-section">
    <div class="container form-page">
      <section class="form-card">
        <h2>Register</h2>
        <p>Create a new student account to submit course feedback.</p>

        <form method="POST" class="form-layout">
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required minlength="2">
          </div>

          <div class="form-group">
            <label for="registerUsername">Username</label>
            <input type="text" id="registerUsername" name="username" placeholder="Enter username" required minlength="5">
          </div>

          <div class="form-group">
            <label for="registerPassword">Password</label>
            <input type="password" id="registerPassword" name="password" placeholder="Enter password" required minlength="8">
          </div>

          <div class="form-group">
            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required minlength="8">
          </div>

          <?php if ($message != ""): ?>
            <p class="form-message" style="color: <?php echo $messageColor; ?>;">
              <?php echo htmlspecialchars($message); ?>
            </p>
          <?php endif; ?>

          <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p class="form-link">
          Already have an account? <a href="login.php">Login</a>
        </p>
      </section>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container">
      <p>CS381 Project | YIC Course Feedback System</p>
    </div>
  </footer>

</body>
</html>