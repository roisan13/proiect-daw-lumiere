<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            margin-bottom: 1rem;
            color: #333;
        }

        p {
            margin: 0.5rem 0;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .signup-link {
            margin-top: 1rem;
        }

        .signup-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php  
            if (isset($_SESSION["login_error"])) {
                echo "<p class='error'>" . $_SESSION["login_error"] . "</p>";
                unset($_SESSION["login_error"]);
            }
        ?>
        <form method="post">
            <p>
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </p>
            <input type="submit" value="Login">
        </form>
        <p class="signup-link">Don't have an account? <a href="/proiect_daw_lumiere/users/create">Sign up</a></p>
    </div>
</body>
</html>
