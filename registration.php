<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/globals.css" />

</head>

<body>
    <main>
        <h1 class="logo">DeckHeaven</h1>
        <div class="wrapper">
            <div class="container">
                <h2>Register</h2>
                <p>Sign up to enjoy the feature of Revolutie</p>

                <form>
                    <input type="text" placeholder="Nick" required />

                    <input type="date" placeholder="Date of birth" required />

                    <input type="email" placeholder="Email" required />

                    <div class="password-wrapper">
                        <input type="password" id="password" placeholder="Password" required />
                        <i class="fa fa-eye toggle-password" onclick="togglePassword()" aria-hidden="true"></i>
                    </div>

                    <button type="submit">Register</button>
                </form>

                <div class="register">
                    Already have an account? <a href="#">Sign in</a>
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