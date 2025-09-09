<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Sign in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/css/globals.css" />
  <link rel="stylesheet" href="/public/css/auth.css" />

</head>

<body class="auth-bg">
  <main class="auth-main">
    <h1 class="logo">DeckHeaven</h1>
    <div class="wrapper">
      <div class="container">
        <h2>Sign in</h2>
        <p>Please login to continue to your account.</p>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
          <p class="auth-msg error">Invalid email or password.</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] === 'missing'): ?>
          <p class="auth-msg error">Please fill all fields.</p>
        <?php elseif (isset($_GET['registered'])): ?>
          <p class="auth-msg success">Registration successful! Please log in.</p>
        <?php endif; ?>
        <form method="POST" action="login_action.php">
          <input class="login-input" type="email" name="email" placeholder="Email" required />
          <div class="password-wrapper">
            <input type="password" id="password" class="login-input" name="password" placeholder="Password" required />
            <i class="fa fa-eye toggle-password" onclick="togglePassword()" aria-hidden="true"></i>
          </div>
          <div class="checkbox">
            <input  type="checkbox" id="keep" />
            <label for="keep">Keep me logged in</label>
          </div>
          <button type="submit">Sign in</button>
        </form>
        <div class="register">
          Don’t have an account? <a href="registration.php">Register</a>
        </div>
      </div>
        <div class="underglow-container">
          <img class="auth-illustration" src="./public/assets/cards/skolim.svg" />
        </div>
    </div>
    <div class="underglow"></div>
  </main>
  <script>
    function togglePassword() {
      const pwd = document.getElementById("password");
      pwd.type = pwd.type === "password" ? "text" : "password";
    }
  </script>
</body>

</html>