<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// DATA
$totalProjects = $conn->query("SELECT COUNT(*) as total FROM projects")->fetch_assoc()['total'];
$ongoingCount = $conn->query("SELECT COUNT(*) as total FROM projects WHERE status='Ongoing'")->fetch_assoc()['total'];

$ongoingProjects = $conn->query("SELECT * FROM projects WHERE status='Ongoing' LIMIT 5");
$upcoming = $conn->query("SELECT * FROM projects WHERE start_date >= CURDATE() LIMIT 5");
$recent = $conn->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5");

$budgetData = $conn->query("
    SELECT 
        SUM(total_budget) as total_budget,
        SUM(spent_amount) as spent,
        SUM(remaining_amount) as remaining
    FROM budget
")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

/* GLOBAL */
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background: url('building.jpeg') no-repeat center center/cover;
    color:white;
    position:relative;
}

/* DARK OVERLAY */
body::before{
    content:"";
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background: rgba(20,10,5,0.85);
    z-index:-1;
}

/* NAVBAR */
.navbar{
    position:fixed;
    top:0;
    left:240px;
    width:calc(100% - 240px);
    height:70px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
    display:flex;
    align-items:center;
    padding:0 25px;
    color:#ffcc80;
    font-size:18px;
    z-index:1000;
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:110px 40px 40px;
}

/* 🔥 CARDS (UPDATED SMALL + ONE LINE) */
.cards{
    display:flex;
    gap:15px;
    flex-wrap:nowrap;
    margin-bottom:25px;
}

.card{
    flex:1;
    max-width:220px;
    padding:18px;
    border-radius:14px;

    background: rgba(255,140,0,0.08);
    backdrop-filter: blur(12px);

    border:1px solid rgba(255,140,0,0.2);
    box-shadow:0 6px 20px rgba(0,0,0,0.5);

    text-align:center;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    font-size:22px;
    color:#ffb74d;
}

.card p{
    font-size:13px;
    color:#ddd;
}

/* MOBILE FIX */
@media(max-width:900px){
    .cards{
        flex-wrap:wrap;
    }
}

/* HEADINGS */
h2{
    margin-top:35px;
    font-size:18px;
    color:#ffcc80;
    border-left:5px solid #ff9800;
    padding-left:10px;
}

/* TABLE */
.table-container{
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(12px);
    border-radius:12px;
    padding:12px;
    margin-top:10px;
}

.table{
    width:100%;
    border-collapse:collapse;
}

.table th{
    background: rgba(255,140,0,0.2);
    padding:10px;
    color:#ffcc80;
    text-align:left;
}

.table td{
    padding:10px;
    border-bottom:1px solid rgba(255,255,255,0.1);
}

.table tr:hover{
    background: rgba(255,140,0,0.08);
}

/* STATUS COLORS */
.status-ongoing{
    color:#ffa726;
    font-weight:bold;
}
.status-completed{
    color:#66bb6a;
    font-weight:bold;
}
.status-pending{
    color:#ff7043;
    font-weight:bold;
}

</style>

</head>
<body>

<?php include 'sidebar.php'; ?>

<!-- NAVBAR -->
<div class="navbar">
    🏗 Dashboard
</div>

<div class="content">

<!-- CARDS -->
<div class="cards">

<div class="card">
<h3><?php echo $totalProjects; ?></h3>
<p>Total Projects</p>
</div>

<div class="card">
<h3><?php echo $ongoingCount; ?></h3>
<p>Ongoing</p>
</div>

<div class="card">
<h3>₹ <?php echo $budgetData['spent'] ?? 0; ?></h3>
<p>Budget Used</p>
</div>

<div class="card">
<h3>₹ <?php echo $budgetData['remaining'] ?? 0; ?></h3>
<p>Remaining</p>
</div>

</div>

<!-- ONGOING -->
<h2>Ongoing Projects</h2>
<div class="table-container">
<table class="table">
<tr><th>Name</th><th>Location</th><th>Status</th></tr>

<?php while($row = $ongoingProjects->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['location']; ?></td>
<td class="status-<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>
</tr>
<?php } ?>

</table>
</div>

<!-- UPCOMING -->
<h2>Upcoming Projects</h2>
<div class="table-container">
<table class="table">
<tr><th>Name</th><th>Start Date</th><th>Status</th></tr>

<?php while($row = $upcoming->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['start_date']; ?></td>
<td class="status-<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>
</tr>
<?php } ?>

</table>
</div>

<!-- RECENT -->
<h2>Recent Projects</h2>
<div class="table-container">
<table class="table">
<tr><th>Name</th><th>Type</th><th>Status</th></tr>

<?php while($row = $recent->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['type']; ?></td>
<td class="status-<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>
</tr>
<?php } ?>

</table>
</div>

</div>

</body>
</html>