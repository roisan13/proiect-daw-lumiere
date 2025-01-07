<?php
require_once "app/models/users.php";

// Load Composer's autoloader
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class UserController{
    public static function index() {
        $users = User::getAllUsers();
        require_once "app/views/users/index.php";
    }

    public static function show() {
        $user_id = $_GET['id'];
        $user = User::getUser($user_id);

        if ($user) {
            require_once "app/views/users/show.php";
        } else {
            $_SESSION['error'] = "User not found";
            require_once "app/views/404.php";
        }
    }

    static function data_validation() {
        $errors = [];
        $len_name = strlen($_POST['last_name']);
        if ($len_name < 1 || $len_name > 32) {
            $errors['last_name_error'] = 'Last name must be between 1 and 32 characters';  
        }
        if (strpos($_POST['email'], '@') === false) {
            $errors['email_error'] = 'Invalid email';
        }
        if (isset($_POST['password']) && strlen($_POST['password']) < 8) {
            $errors['password_error'] = 'Password must be at least 8 characters';
        }
        if (isset($_POST['role_id']) && !UserRole::getRole($_POST['role_id'])) {
            $errors['role_error'] = 'Invalid role';
        }

        return $errors;
    }

    public static function create() {
        if (isset($_POST["is_post"])){
            
            // POST => create user
            $_SESSION["create_user"]["user"] = $_POST;

            $errors = self::data_validation();
            if (count($errors)){
                $_SESSION["create_user"]["errors"] = $errors;
                header("Location: create");
                return;
            }
           $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

            User::createUser(
                htmlentities($_POST["first_name"]), 
                htmlentities($_POST["last_name"]),
                htmlentities($_POST["email"]), 
                $pass,
                htmlentities($_POST["role_id"])
            );
            header("Location: /proiect_daw_lumiere");
        }

        // GET => show form
        if (!isset($_SESSION["create_user"]["user"])){
            $_SESSION["create_user"]["user"] = [
                "first_name" => "",
                "last_name" => "",
                "email" => ""
            ];
        }
        
        $roles  = UserRole::getAllRoles();
        require_once "app/views/users/create.php";
    }


    public static function contact() {
        
        require_once "config/keys.php";
                
        $mail = new PHPMailer(true);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Collect and sanitize form data
            $name = htmlspecialchars(trim($_POST["name"]));
            $email = htmlspecialchars(trim($_POST["email"]));
            $subject = htmlspecialchars(trim($_POST["subject"]));
            $message = htmlspecialchars(trim($_POST["message"]));
            $recaptchaResponse = $_POST['g-recaptcha-response'];
        
            // Validate form inputs
            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $error = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Please enter a valid email address.";
            } elseif (empty($recaptchaResponse)) {
                $error = "Please complete the reCAPTCHA verification.";
            } else {
                // Verify reCAPTCHA
                $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
                $recaptchaVerify = file_get_contents($recaptchaUrl . '?secret=' . $recaptchaSecret . '&response=' . $recaptchaResponse);
                $recaptchaData = json_decode($recaptchaVerify);
                
                // var_dump($recaptchaData);
                if (!$recaptchaData->success) {
                    $error = "reCAPTCHA verification failed. Please try again.";
                } else {
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = "cinemalumierephp@gmail.com";
                        $mail->Password   = $googleAppPassword;
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        $mail->setFrom($email, $name);
                        $mail->addAddress('cinemalumierephp@gmail.com', 'Moderator');
                        $mail->Subject = $subject;
                        $mail->Body    = "You have received a new message from the contact form:\n\n".
                                         "Name: $name\n".
                                         "Email: $email\n".
                                         "Subject: $subject\n".
                                         "Message: $message";
                

                        $mail->send();
                        $mail->clearAddresses();
                        $mail->setFrom($email, "Cinema Lumiere");
                        $mail->addAddress($email, "Cinema Lumiere");
                        $mail->Subject = "Thank you for contacting Cinema Lumiere!";
                        $mail->Body    = "Hello $name,\n\n".
                                         "Thank you for reaching out to us. Here is a copy of your message:\n\n".
                                         "Subject: $subject\n".
                                         "Message: $message\n\n".
                                         "We will get back to you as soon as possible.\n\n".
                                         "Best regards,\n".
                                         "Cinema Lumiere Team";
                        $mail->send();
                    
                        $success = "Thank you for your message! A copy of your message has been sent to your email.";
                    } catch (Exception $e) {
                        $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }   
        }
        require_once "app/views/contact.php";
    }

}
?>