<style>

.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background color:brown;
    color: white;
    padding-top: 20px;
    overflow: hidden;
}

/* DARK OVERLAY */
.sidebar::before {
    content: "";
    position: absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.75);
}

/* CONTENT ABOVE OVERLAY */
.sidebar * {
    position: relative;
    z-index: 2;
}

/* TITLE */
.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 20px;
    padding: 0 10px;
}

/* LINKS */
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #ddd;
    text-decoration: none;
    font-size: 15px;
    transition: 0.3s;
    border-left: 3px solid transparent;
}

/* HOVER EFFECT */
.sidebar a:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-left: 3px solid #ff9800;
    padding-left: 25px;
}

/* ACTIVE LINK */
.sidebar a.active {
    background: rgba(255,152,0,0.2);
    color: #fff;
    border-left: 3px solid #ff9800;
}

/* LOGOUT STYLE */
.sidebar a:last-child {
    position: absolute;
    bottom: 20px;
    width: 100%;
    background: rgba(255,0,0,0.2);
}

.sidebar a:last-child:hover {
    background: rgba(255,0,0,0.4);
}

</style>


<div class="sidebar">
    <h2>🏗️ Shri Devi Construction</h2>

    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'';?>">Dashboard</a>
    <a href="projects.php"class="<?php echo basename($_SERVER['PHP_SELF'])=='projects.php'?'active':'';?>">Projects</a>
    <a href="resources.php"class="<?php echo basename($_SERVER['PHP_SELF'])=='resources.php'?'active':'';?>">Resources</a>
    <a href="budget.php"class="<?php echo basename($_SERVER['PHP_SELF'])=='budget.php'?'active':'';?>">Budget</a>
    <a href="progress.php"class="<?php echo basename($_SERVER['PHP_SELF'])=='progress.php'?'active':'';?>">Progress</a>
    <a href="tasks.php"class="<?php echo basename($_SERVER['PHP_SELF'])=='tasks.php'?'active':'';?>">Tasks</a>
    <a href="logout.php">Logout</a>
</div>