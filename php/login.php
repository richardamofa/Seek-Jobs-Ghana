<?php
session_start();
require_once "db_connect.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([":email" => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["loggedin"] = true;
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["fullname"] = $user["fullname"];

        header("location: welcome.php");
        exit();
    }

    if (!$user) {
        $error = "No account found with that email. <a href='signup.php'>Sign up here</a>";
    } elseif (!password_verify($password, $user["password"])) {
        $error = "Wrong password. Try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/php/img/letter-s.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Login</title>
    <style>
        :root {
            --primary-green: #77e64c;
            --secondary-green: #58c853;
            --dark-bg: #1c1a1a;
            --light-bg: #e3e3e3;
            --white: #ffffff;
            --text-color: #235347;
            --text-dark: #333;
            --text-light: #ffffff;
            --shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            --radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background: linear-gradient(to right, #e2e2e2, #c3f1c3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }

        .container p {
            font-size: 1rem;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        button {
            text-decoration: none;
            background-color: #58c853;
            color: #fff;
            font-size: 1rem;
            padding: 10px 45px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4ab74a;
            transition: background-color 0.3s ease;
        }

        .container form {
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .form-container {
            text-align: center;
            position: absolute;
            top: 0;
            height: 100%;
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .form-container h1 {
            font-family: Poppins, sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .social-icons {
            margin: 20px 0;
        }

        .social-icons a {
            color: var(--secondary-green);
            font-size: 1.5rem;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 40px;
            height: 40px;
        }

        .fa-google {
            background: conic-gradient(from -45deg, #ea4335 110deg,
                                       #4285f4 90deg 180deg,
                                       #34a853 180deg 270deg,
                                       #fbbc05 270deg) 73% 55%/150% 150% no-repeat;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }
        .fa-google:hover { color: #c13515; }
        .fa-facebook { color: #3b5998; }
        .fa-facebook:hover { color: #2d4373; }
        .fa-linkedin-in { color: #0077b5; }
        .fa-linkedin-in:hover { color: #005582; }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .toggle-panel {
            background-image: url(img/login.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: absolute;
            width: 100%;
            height: 100%;
            padding: 0 30px;
            text-align: center;
        }

        .toggle-content {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 80%;
            transform: translate(-50%, -50%);
            color: var(--text-light);
        }

        .toggle-content h3 {
            font-size: 2rem;
            font-weight: bolder;
            margin-bottom: 10px;
        }

        .toggle-content p {
            font-size: 1rem;
            margin-top: 20px;
        }

        .forgotpassword {
            font-size: 14px;
            color: var(--text-color);
            margin: 10px 0 0 5px;
            text-decoration: none;
            border-bottom: 0.5px transparent;
            transition: border-color 0.3s ease;
        }
        .forgotpassword:hover {
            border-bottom: 0.5px solid var(--text-color);
        }

        .error-message {
            position: fixed;
            top: -80px;
            left: 50%;
            transform: translateX(-50%);
            background: #f8d7da;
            color: #721c24;
            padding: 14px 20px;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            font-size: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            text-align: center;
            z-index: 9999;
            opacity: 0;
            transition: all 0.6s ease;
        }
        .error-message.show {
            top: 20px;
            opacity: 1;
        }
        .error-message.hide {
            opacity: 0;
            top: -80px;
        }
        .error-message a {
            color: #721c24;
            font-weight: 700;
            text-decoration: underline;
        }
        .error-message a:hover {
            color: #a71d2a;
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                min-height: 100vh;
                border-radius: 0;
            }
            .form-container {
                width: 100%;
                height: auto;
                position: relative;
                top: 100px;
                left: auto;
            }
            .form-container h1 {
                font-size: 1.8rem;
            }
            .toggle-container {
                width: 100%;
                height: auto;
                position: relative;
                top: auto;
                left: auto;
                border-radius: 0;
            }
            .toggle-panel {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form action="login.php" method="post">
                <h1>Login to Seek Jobs</h1>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-google"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span style="font-size:12px;">or use email for registration</span>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword()"></span>
                <a href="forgot_password.php" class="forgotpassword">Forgot Your Password?</a>
                <button type="submit">Login</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle-panel">
                <div class="toggle-content">
                    <h3>Welcome Back to Seek Jobs</h3>
                    <p>
                        We are glad to see you again! Please log in to your account.<br>
                        Don't have an account? <a href="/php/signup.php">Sign Up</a><br>
                        By logging in, you agree to our <a href="#">Terms of Service</a>
                        and <a href="#">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <script>
        // Password toggle
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function () {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Toast animation for error
        document.addEventListener("DOMContentLoaded", function () {
            const errorBox = document.querySelector(".error-message");
            if (errorBox) {
                setTimeout(() => {
                    errorBox.classList.add("show");
                }, 200);

                setTimeout(() => {
                    errorBox.classList.remove("show");
                    errorBox.classList.add("hide");
                }, 4200);
            }
        });
    </script>
</body>
</html>
