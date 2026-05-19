<?php
session_start();
require_once "includes/db.php";

$message = "";

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {

        $message = "Invalid request.";

    } else {

        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $role = trim($_POST["role"]);

        if ($username == "" || $password == "" || $role == "") {

            $message = "Please fill in all fields.";

        } else {

            $stmt = $pdo->prepare("
                SELECT * FROM users
                WHERE username = :username AND password = :password AND role = :role
            ");

            $stmt->execute([
                ":username" => $username,
                ":password" => $password,
                ":role" => $role
            ]);

            $user = $stmt->fetch();

            if ($user) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                if ($role == "admin") {
                    header("Location: admin-dashboard.php");
                } else {
                    header("Location: index.php");
                }

                exit();

            } else {

                $message = "Invalid login information.";

            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - YIC Course Feedback System</title>
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
      <a href="login.php" class="active">Login</a>
      <a href="register.php" class="register-btn">Register</a>
    </nav>
  </div>
</header>

<main class="page-section">
  <div class="container form-page">

    <section class="form-card">
      <h2>Login</h2>
      <p>Login as a student or admin user.</p>

      <form method="POST" class="form-layout">

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">

        <div class="form-group">
          <label>Username</label>
          <input type="text" name="username" placeholder="Enter username">
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter password">
        </div>

        <div class="form-group">
          <label>Role</label>

          <select name="role">
            <option value="">Select role</option>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <?php if ($message != ""): ?>
          <p class="form-message" style="color:red;">
            <?php echo htmlspecialchars($message); ?>
          </p>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">
          Login
        </button>

      </form>

      <p class="form-link">
        Don't have an account?
        <a href="register.php">Register</a>
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