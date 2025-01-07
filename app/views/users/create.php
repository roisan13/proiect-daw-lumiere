<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #333;
        }

        form p {
            margin: 1rem 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 1rem;
            display: block;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create User</h1>
        <form action="create" method="post">
            <input type="hidden" name="is_post" value="1">

            <p>
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" 
                value="<?= htmlspecialchars($_SESSION['create_user']['user']['first_name'] ?? '') ?>">
            </p>

            <p>
                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name"
                value="<?= htmlspecialchars($_SESSION['create_user']['user']['last_name'] ?? '') ?>">
            </p>
            <p class="error">
                <?php 
                if (isset($_SESSION['create_user']["errors"]['last_name_error'])):
                    echo $_SESSION['create_user']["errors"]['last_name_error'];
                    unset($_SESSION['create_user']["errors"]['last_name_error']);
                endif;
                ?>
            </p>

            <p>
                <label for="email">Email</label>
                <input type="text" name="email" id="email"
                value="<?= htmlspecialchars($_SESSION['create_user']['user']['email'] ?? '') ?>">
            </p>
            <p class="error">
                <?php 
                if (isset($_SESSION['create_user']["errors"]['email_error'])):
                    echo $_SESSION['create_user']["errors"]['email_error'];
                    unset($_SESSION['create_user']["errors"]['email_error']);
                endif;
                ?>
            </p>

            <p>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </p>
            <p class="error">
                <?php 
                if (isset($_SESSION['create_user']["errors"]['password_error'])):
                    echo $_SESSION['create_user']["errors"]['password_error'];
                    unset($_SESSION['create_user']["errors"]['password_error']);
                endif;
                ?>
            </p>

            <?php if (isset($_SESSION["request_user"]) && User::hasPermission($_SESSION["request_user"]["id"], "create_movie")): ?>
                <p>
                    <label for="role">Role</label>
                    <select name="role_id" id="role_id">
                        <?php foreach ($roles as $role) : ?>
                            <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            <?php else: ?>
                <input type="hidden" name="role_id" value="2">
            <?php endif; ?>

            <input type="submit" value="Create">
        </form>
    </div>
</body>
</html>
