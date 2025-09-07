<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Sign in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./public/css/globals.css" />

</head>

<body>
  <main>
    <h1 class="logo">DeckHeaven</h1>
    <div class="wrapper">
      <div class="container">
        <h2>Sign in</h2>
        <p>Please login to continue to your account.</p>
        <form>
          <input type="email" placeholder="Email" required />

          <div class="password-wrapper">
            <input type="password" id="password" placeholder="Password" required />
            <i class="fa fa-eye toggle-password" onclick="togglePassword()" aria-hidden="true"></i>
          </div>

          <div class="checkbox">
            <input type="checkbox" id="keep" />
            <label for="keep">Keep me logged in</label>
          </div>

          <button type="submit">Sign in</button>
        </form>

        <div class="register">
          Don’t have an account? <a href="#">Register</a>
        </div>
      </div>
      <div class="underglow-container">
        <img src="./public/assets/cards/skolim.svg" style="width: 40%" />

      </div>
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