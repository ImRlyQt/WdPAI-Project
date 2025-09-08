<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/css/globals.css" />
    <link rel="stylesheet" href="/public/css/auth.css" />

</head>

<body class="auth-bg">
    <main class="auth-main">
        <h1 class="logo">DeckHeaven</h1>
        <div class="wrapper">
            <div class="container">
                <h2>Register</h2>
                <p>Sign up to enjoy the feature of Revolutie</p>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
                  <p style="color:#f55">Email already registered.</p>
                <?php elseif (isset($_GET['error']) && $_GET['error'] === 'missing'): ?>
                  <p style="color:#f55">Please fill all fields.</p>
                <?php endif; ?>
                <form method="POST" action="register_action.php">
                    <input class="login-input" type="text" name="nick" placeholder="Nick" required />
                    <input class="login-input" type="date" name="dob" placeholder="Date of birth" required />
                    <input class="login-input" type="email" name="email" placeholder="Email" required />
                    <div class="password-wrapper">
                        <input class="login-input" type="password" id="password" name="password" placeholder="Password" required />
                        <i class="fa fa-eye toggle-password" onclick="togglePassword()" aria-hidden="true"></i>
                    </div>
                    <button type="submit">Register</button>
                </form>
                <div class="register">
                    Already have an account? <a href="login.php">Sign in</a>
                </div>
            </div>
            <div class="underglow-container">
                <img src="./public/assets/cards/brakSkolima.svg" style="width: 40%" />
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