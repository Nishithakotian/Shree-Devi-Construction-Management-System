<?php
session_start();

if(isset($_SESSION['user'])){
    session_unset();
    session_destroy();
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Logout</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

/* GLOBAL */
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background: url('cont.jpg') no-repeat center center/cover;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    position:relative;
}

/* DARK BROWN OVERLAY */
body::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background: rgba(30,15,5,0.85);
    z-index:-1;
}

/* LOGOUT CARD */
.logout-box{
    width:400px;
    padding:40px;
    border-radius:20px;
    text-align:center;

    background: rgba(80,50,30,0.35);
    backdrop-filter: blur(15px);

    border:1px solid rgba(255,140,0,0.3);
    box-shadow:0 10px 35px rgba(0,0,0,0.8);

    animation: fadeIn 0.8s ease;
}

/* ICON */
.icon{
    font-size:50px;
    margin-bottom:15px;
    color:#ffb74d;
}

/* TITLE */
.logout-box h2{
    margin-bottom:10px;
    color:#ffcc80;
}

/* TEXT */
.logout-box p{
    font-size:14px;
    opacity:0.85;
}

/* LOADER BAR */
.loader{
    margin-top:20px;
    height:5px;
    width:100%;
    background: rgba(255,255,255,0.1);
    border-radius:10px;
    overflow:hidden;
}

.loader span{
    display:block;
    height:100%;
    width:0%;
    background: linear-gradient(90deg,#ff9800,#ffb74d);
    animation: load 2s linear forwards;
}

/* ANIMATIONS */
@keyframes load{
    from{width:0%;}
    to{width:100%;}
}

@keyframes fadeIn{
    from{opacity:0; transform:scale(0.9);}
    to{opacity:1; transform:scale(1);}
}

</style>

</head>

<body>

<div class="logout-box">

    <div class="icon">👋</div>

    <h2>Logged Out Successfully</h2>
    <p>Redirecting to login page...</p>

    <div class="loader">
        <span></span>
    </div>

</div>

<script>
    setTimeout(function(){
        window.location.href = 'login.php';
    }, 2000);
</script>

</body>
</html>