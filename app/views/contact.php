<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form with reCAPTCHA v2</title>
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LfGwZ8qAAAAAKIcj2eE1QaoynaseOYLkfVEu3tU"></script>
    <script>
    function onSubmit(token) {
        document.getElementById("contact-form").submit();
    }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .error {
            color: #e74c3c;
        }
        .success {
            color: #2ecc71;
        }
        .g-recaptcha {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
        <h2>Contact Us</h2>
        <?php if (isset($error)): ?>
            <p class="message error"><?= $error ?></p>
        <?php elseif (isset($success)): ?>
            <p class="message success"><?= $success ?></p>
        <?php endif; ?>

        <form id="contact-form" action="contact" method="POST">
            <div class="form-group">
                <label for="name">Name/Company Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name or company name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email address" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" placeholder="Enter your message" required></textarea>
            </div>
            <button class="g-recaptcha btn"
            data-sitekey="6LfGwZ8qAAAAAKIcj2eE1QaoynaseOYLkfVEu3tU"
            data-callback='onSubmit'
            data-action='submit'>
            Send message
            </button>
        </form>
    </div>
</body>
</html>