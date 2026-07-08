<?php
session_start();
include 'db.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        if($password == $row['password']){
            $_SESSION['user'] = $row['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>SHRI DEVI CONSTRUCTION</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    height:100vh;
    background: url('build.jpeg') no-repeat center center/cover;
    display:flex;
    justify-content:center;
    align-items:center;
    position:relative;
}

body::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.6);
}

.login-box{
    position:relative;
    width:400px;
    padding:40px;
    border-radius:15px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(15px);
    box-shadow:0 10px 30px rgba(0,0,0,0.5);
    color:white;
    text-align:center;
}

.login-box h1{
    margin-bottom:5px;
    font-size:20px;
}

.login-box p{
    font-size:13px;
    margin-bottom:20px;
    opacity:0.8;
}

.input-group {
    position: relative;
    margin-bottom: 15px;
}

.input-group input {
    width: 100%;
    padding: 10px 40px 10px 38px; /* space for both icons */
    border: none;
    border-radius: 8px;
    outline: none;
}

/* LEFT ICON (lock/user) */
.input-group i.fa-lock,
.input-group i.fa-user {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #ccc;
}


/* EYE ICON */
.toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #ccc;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    background: linear-gradient(135deg,#ff7e00,#ffb347);
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

.error{
    background:rgba(255,0,0,0.2);
    padding:8px;
    border-radius:5px;
    margin-bottom:10px;
    font-size:13px;
}
</style>
</head>

<body>

<div class="login-box">

    <h1>🏗️ Shri Devi Construction</h1>
    <p>Building with care. Precision. Purpose.</p>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">

        <!-- USERNAME -->
        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <!-- PASSWORD WITH EYE ICON -->
        <div class="input-group">
            <i class="fa fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
        </div>

        <button name="login">Login</button>

    </form>

</div>

<!-- JAVASCRIPT -->
<script>
function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>