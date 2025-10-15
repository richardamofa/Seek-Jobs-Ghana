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
    <link rel="stylesheet" href="/css/signup.css">
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
    --dark-bg: #1c1a1a;
    --light-bg: #e3e3e3;
    --white: #ffffff;
    --text-color: #235347;
    --text-dark: #333;
    --text-light: #ffffff;
    --shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    --radius: 10px;
}

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #c3f1c3;
    font-family: 'Montserrat', 'Poppins', sans-serif;
    background: var(--accent);
    color: var(--text-color);
    min-height: 80vh;
}
.container {
    width: 90%;
    margin: auto;
}
main {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}
.sign {
    margin: 0;
    padding: 0;
}
.sign p {
    color: var(--text-color);
    margin: 0;
}
.sign h2 {
    margin: 0.5rem;
}
.sign img {
    width: 300px ; 
    height: 300px; 
    border-radius: 1rem; 
    display: block;
}
.form-side {
    background: #e3e3e3;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    margin: 30px 60px;
    padding: 0 40px;
    width: 80%;
    height: 100%;
}
.social-icons {
    margin: 20px 0;
}
.social-icons a {
    text-decoration: none;
    color: var(--dark-bg);
    border: 1px solid rgba(32, 97, 27, 0.817);
    border-radius: 20%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 3px;
    width: 40px;
    height: 40px;
    transition: color 0.2s, background 0.2s;
}
.social-icons a:hover {
    color: var(--text-light);
    background: var(--text-color);
}

.form-side input {
    font-family: Montserrat;
    background: #eee;
    border: none;
    margin: 8px 0;
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    width: 100%;
    outline: none;
}
.form-side input:focus-visible {
    outline: 1px solid var(--text-color);
    outline-offset: 1px;
}

.submit {
    color: var(--text-light);
    background: var(--text-color);
    border: 1px solid var(--dark-bg);
    margin-top: 10px;
    padding: 10px 15px;
    font-family: 'Poppins';
    font-size: 1rem;
    font-weight: 600;
    border-radius: 1rem;
    width: 100%;
    cursor: pointer;
    transition: color 0.2s ease-in;
}

.submit:hover {
    background: #3a6b60;
    transition: 0.3s ease;
}

/* Footer */
footer {
    background: var(--text-color);
    color: #888;
    text-align: center;
    padding: 2.5rem 0 1rem 0;
    border-top: 1px solid #e3e3e3;
    margin-top: 0;
}
.social-icons-footer a {
    color: var(--text-light);
    font-size: 1.5rem;
    margin: 0 0.5rem;
    text-align: center;
    transition: color 0.2s;
}
.social-icons-footer a:hover {
    color: var(--secondary-green);
}
.footer p {
    margin: 0.5rem 0 0 0;
    font-size: 0.98rem;
}
.footer-links {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    padding: 0.5rem 0;
    font-size: 1rem;
}
.footer-links a {
    color: var(--primary-green);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
.footer-links a:hover {
    color: var(--secondary-green);
    text-decoration: underline;
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
        <hr><p>or</p>
        
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
