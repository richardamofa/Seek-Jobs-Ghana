<?php
session_start();
require_once "db_connect.php";

//already loggedin
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match. Try again.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        if ($stmt->fetch()) {
            $error = "An account with that email already exists. <a href='login.php'>Log in here</a>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (fullname, username, phone, email, password) 
                    VALUES (:fullname, :username, :phone, :email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":fullname" => $fullname,
                ":username" => $username,
                ":phone" => $phone,
                ":email" => $email,
                ":password" => $hashed_password
            ]);

            // Auto-login after signup
            $_SESSION["loggedin"] = true;
            $_SESSION["fullname"] = $fullname;
            $_SESSION["email"] = $email;

            header("location: welcome.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/css/img/letter-s.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Sen:wght@400..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM8U4j7z5l5e5c5e5e5e5e5e5e5e5e5e5e5e" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Sign Up</title>
</head>
<style>
:root {
    --primary-green: #77e64c;
    --secondary-green: #58c853;
    --accent: #6edc7b;
    --dark-bg: #1c1a1a;
    --light-bg: #f5f7f6;
    --white: #ffffff;
    --text-color: #235347;
    --text-dark: #333;
    --text-light: #ffffff;
    --shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    --radius: 14px;
}

/* RESET */
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Montserrat', 'Poppins', sans-serif;
    background: linear-gradient(135deg, #c9f7c9, #eaffef);
    color: var(--text-color);
    min-height: 100vh;
}

/* CONTAINER */
.container {
    width: 92%;
    max-width: 1200px;
    margin: auto;
}

main {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    align-items: center;
    justify-content: center;
    padding: 2rem 0;
}

/* LEFT SIDE */
.sign p {
    opacity: 0.85;
}

.sign h2 {
    margin: 0.8rem 0;
    font-size: 2rem;
}

.sign img {
    width: 300px; 
    height: auto;
    border-radius: 1.2rem; 
    display: block;
    box-shadow: var(--shadow);
}

/* FORM SIDE (SAME STRUCTURE, BETTER LOOK) */
.form-side {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(10px);
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius);
    margin: 30px 40px;
    padding: 2.5rem 2rem;
    width: 80%;
    box-shadow: var(--shadow);
    transition: transform .25s ease, box-shadow .25s ease;
}

.form-side:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.12);
}

/* INPUTS */
.form-side input {
    background: #f1f3f2;
    border: 1px solid transparent;
    margin: 10px 0;
    padding: 14px 16px;
    font-size: 14px;
    border-radius: 10px;
    width: 100%;
    outline: none;
    transition: border .2s, background .2s;
}

.form-side input:focus {
    border: 1px solid var(--accent);
    background: #ffffff;
}

/* SOCIAL ICONS */
.social-icons {
    margin: 22px 0;
}

.social-icons a {
    text-decoration: none;
    color: var(--dark-bg);
    border: 1px solid #cde7cf;
    border-radius: 50%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 6px;
    width: 42px;
    height: 42px;
    background: #fff;
    transition: all 0.25s ease;
}

.social-icons a:hover {
    color: white;
    background: var(--accent);
    transform: scale(1.08);
}

/* BUTTON */
.submit {
    margin-top: 1rem;
    color: var(--text-light);
    background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
    border: none;
    padding: 14px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 14px;
    width: 100%;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .2s ease;
}

.submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.18);
}

/* FOOTER */
footer {
    background: #1f3d36;
    color: #c9d7d3;
    text-align: center;
    padding: 2rem 1rem;
    margin-top: 2rem;
}

.footer-links {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    font-size: 0.95rem;
}

.footer-links a {
    color: var(--primary-green);
    text-decoration: none;
}

.footer-links a:hover {
    text-decoration: underline;
}

/* RESPONSIVE */
@media (max-width: 780px) {
    .form-side, .sign {
        width: 100%;
        margin: 0;
        padding: 1.5rem;
    }
}

@media (max-width: 580px) {
    .sign img {
        width: 240px;
    }

    .submit {
        font-size: 0.85rem;
        padding: 12px;
    }

    .footer-links {
        gap: 1rem;
        font-size: 0.8rem;
    }
}


/* MOBILE */
@media (max-width: 768px) {
    main {
        flex-direction: column;
        padding: 1.5rem 0;
    }

    .form-side {
        width: 100%;
        padding: 2rem;
    }

    .sign img {
        width: 260px;
    }
}



@media (max-width: 780px) {
    .form-side, .sign {
        width: 100%;
        margin: 0;
        padding: 20px;
    }
}
@media (max-width: 580px) {
    .form-side {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 2rem;
    }
    .form-side input {
        width: 100%;
        height: auto;
    }
    .sign {
        max-width: max-content;
    }
    .sign img {
        width: 400px;
    }
    .submit {
        width: 100%;
        margin: 0;
        padding: 0.5rem;
        font-size: 0.65rem;
    }
    footer {
        width: 100%;
        max-width: 500px;
        padding: 0.5rem;
    }
    .footer-links {
        gap: 1rem;
        padding: 0;
        font-size: 0.65rem;
    }
    .social-icons-footer{
    font-size: 0.65rem;
    margin: 0.5rem;
    }
}
</style>
<body>
    <div class="container">
    <main>
    <aside style="flex:2;min-width:300px;">
    <div class="form-side">
    <form method="POST" action="signup.php">
        <h3>Register with</h3>
        <div class="social-icons">
            <a href="#" class="icon"><i class="fa-brands fa-google"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
        <hr/>
        <p>or</p>
        
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>

        <button type="submit" class="submit">Sign Up</button>

        <p class="terms">By signing up, you agree to our 
            <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
        </p>
        <p>Already have an account? <a href="login.php" class="login-link">Log In</a></p>
    </form> 
    </div>
    </aside>
          <section class="frame-section" style="flex:1;min-width:300px;">
            <div class="sign">
                <div class="sign-content">
                <h2>Join Seek Jobs Ghana</h2>
                <p>Create an account to access top job opportunities across Ghana. Get matched with roles that fit your skills and career goals.</p>
                </div>
                <img src="img/signup.jpg">
            </div>
          </section>
        </main>
    </div>

<footer>
    <p>&copy; 2025 Seek Jobs. All rights reserved.</p>
        <div class="social-icons-footer">
            <a href="#" target="_blank" class="icon"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" target="_blank" class="icon"><i class="fa-brands fa-x"></i></a>
            <a href="https://www.instagram.com/_.ricchie/" target="_blank" class="icon"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.linkedin.com/in/richard-osei-amofa-113414286/?trk=public-profile-join-page" target="_blank" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
    </div>
    <div class="footer-links">
        <a href="#">Privacy Policy</a> |
        <a href="#">Terms of Service</a> |
        <a href="seekjobs.html#contact">Contact Us</a> |
        <a href="seekjobs.html#about">About Us</a>
    </div>
</footer>
</body>
</html>
