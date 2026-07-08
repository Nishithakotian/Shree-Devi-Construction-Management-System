<?php
session_start();
include 'db.php';

/* =========================
   INSERT
========================= */
if(isset($_POST['add'])){

    $pid = $_POST['project_id'];
    $progress = $_POST['progress'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $conn->query("INSERT INTO progress
    (project_id,progress_percent,status,remarks)
    VALUES ('$pid','$progress','$status','$remarks')");

    header("Location: progress.php?success=1");
    exit();
}

/* =========================
   DELETE
========================= */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $conn->query("DELETE FROM progress WHERE id='$id'");

    header("Location: progress.php?deleted=1");
    exit();
}

/* =========================
   FETCH DATA FOR EDIT
========================= */
$editData = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query("SELECT * FROM progress WHERE id='$id'");
    $editData = $result->fetch_assoc();
}

/* =========================
   UPDATE
========================= */
if(isset($_POST['update'])){

    $id = $_POST['id'];

    $pid = $_POST['project_id'];
    $progress = $_POST['progress'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $conn->query("UPDATE progress SET
        project_id='$pid',
        progress_percent='$progress',
        status='$status',
        remarks='$remarks'
        WHERE id='$id'
    ");

    header("Location: progress.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Progress</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

/* GLOBAL */
*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:url('cont.jpg') no-repeat center center/cover;
    color:white;
}

/* OVERLAY */
body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(30,15,5,0.85);
    z-index:-1;
}

/* NAVBAR */
.navbar{
    position:fixed;
    top:0;
    left:240px;
    width:calc(100% - 240px);
    height:70px;

    background:rgba(0,0,0,0.49);
    backdrop-filter:blur(10px);

    display:flex;
    align-items:center;
    padding:0 25px;

    color:#ffcc80;
    font-size:18px;
    font-weight:600;

    z-index:1000;
    box-shadow:0 5px 20px rgba(0,0,0,0.7);
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:110px 30px;
}

/* TITLE */
h2{
    color:#ffcc80;
    border-left:5px solid #ff9800;
    padding-left:10px;
}

/* CONTAINER */
.container{
    display:flex;
    flex-direction:column;
    gap:25px;
}

/* FORM BOX */
.form-box{
    width:100%;

    padding:25px;
    border-radius:15px;

    background:rgba(80,50,30,0.35);
    backdrop-filter:blur(15px);

    border:1px solid rgba(255,140,0,0.3);
    box-shadow:0 10px 30px rgba(0,0,0,0.7);
}

/* FORM GRID */
form{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:20px;
}

/* FORM GROUP */
.form-group{
    display:flex;
    flex-direction:column;
}

/* LABEL */
.form-group label{
    margin-bottom:6px;
    font-weight:500;
}

/* INPUTS */
.form-group input,
.form-group select,
.form-group textarea{
    width:100%;
    padding:12px;

    border-radius:8px;
    border:none;
    outline:none;

    background:rgba(255,255,255,0.1);
    color:white;

    font-size:14px;
}

/* TEXTAREA FULL WIDTH */
.full-width{
    grid-column:1 / 3;
}

.form-group textarea{
    resize:none;
    height:80px;
}

.form-group select option{
    color:black;
}

/* BUTTON AREA */
.form-btn{
    grid-column:1 / 3;
}

/* BUTTON */
button{
    width:100%;
    padding:13px;

    border:none;
    border-radius:8px;

    background:linear-gradient(135deg,#ff9800,#ffb74d);
    color:white;
    font-weight:600;

    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.03);
}

/* TABLE BOX */
.table-box{
    width:100%;

    padding:25px;
    border-radius:15px;

    background:rgba(40,25,15,0.6);
    backdrop-filter:blur(12px);

    border:1px solid rgba(255,140,0,0.25);
    box-shadow:0 10px 35px rgba(0,0,0,0.8);

    overflow-x:auto;
}

/* TABLE */
.table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

.table th{
    background:rgba(255,140,0,0.25);
    padding:12px;
    color:#ffcc80;
    text-align:left;
}

.table td{
    padding:12px;
    border-bottom:1px solid rgba(255,255,255,0.1);
    vertical-align:middle;
}

.table tr:hover{
    background:rgba(255,140,0,0.08);
}

/* PROGRESS BAR */
.progress-bar{
    background:rgba(255,255,255,0.1);
    border-radius:20px;
    overflow:hidden;
    height:12px;
    margin-top:5px;
}

.progress-fill{
    height:100%;
    background:linear-gradient(135deg,#ff9800,#ff5722);
}

/* STATUS */
.status-ongoing{
    color:#ffa726;
    font-weight:bold;
}

.status-completed{
    color:#66bb6a;
    font-weight:bold;
}

/* ACTIONS */
.actions{
    display:flex;
    flex-direction:column;
    gap:8px;
    align-items:flex-start;
}

/* BUTTONS */
.action-btn{
    display:inline-block;

    min-width:70px;
    text-align:center;

    padding:8px 12px;
    border-radius:6px;

    text-decoration:none;
    color:white;
    font-size:13px;
    font-weight:500;

    transition:0.3s;
}

.action-btn:hover{
    transform:translateY(-2px);
}

/* EDIT */
.edit-btn{
    background:#42a5f5;
}

/* DELETE */
.delete-btn{
    background:#ef5350;
}

/* SUCCESS */
.success{
    color:#81c784;
    margin-bottom:10px;
    font-weight:500;
}

/* RESPONSIVE */
@media(max-width:700px){

    .navbar{
        left:0;
        width:100%;
    }

    .content{
        margin-left:0;
        padding:100px 15px 20px;
    }

    form{
        grid-template-columns:1fr;
    }

    .full-width,
    .form-btn{
        grid-column:auto;
    }
}

</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="navbar">
    📊 Progress Tracking
</div>

<div class="content">

<h2>Progress Tracking</h2>

<?php
if(isset($_GET['success'])){
    echo "<p class='success'>Progress Added Successfully!</p>";
}

if(isset($_GET['updated'])){
    echo "<p class='success'>Progress Updated Successfully!</p>";
}

if(isset($_GET['deleted'])){
    echo "<p class='success'>Progress Deleted Successfully!</p>";
}
?>

<div class="container">

<!-- FORM -->
<div class="form-box">

<?php if($editData){ ?>
<h3>Edit Progress</h3>
<?php } else { ?>
<h3>Update Progress</h3>
<?php } ?>

<form method="POST">

<?php if($editData){ ?>
<input type="hidden" name="id"
value="<?php echo $editData['id']; ?>">
<?php } ?>

<div class="form-group">
<label>Project</label>

<select name="project_id" required>

<?php
$p = $conn->query("SELECT * FROM projects");

while($row = $p->fetch_assoc()){
?>

<option value="<?php echo $row['id']; ?>"

<?php
if(($editData['project_id'] ?? '') == $row['id']){
    echo "selected";
}
?>>

<?php echo $row['name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Progress (%)</label>

<input type="number"
name="progress"
min="0"
max="100"
value="<?php echo $editData['progress_percent'] ?? ''; ?>"
required>

</div>

<div class="form-group">
<label>Status</label>

<select name="status">

<option value="Ongoing"
<?php if(($editData['status'] ?? '')=="Ongoing") echo "selected"; ?>>
Ongoing
</option>

<option value="Completed"
<?php if(($editData['status'] ?? '')=="Completed") echo "selected"; ?>>
Completed
</option>

</select>

</div>

<div class="form-group full-width">
<label>Remarks</label>

<textarea name="remarks"><?php echo $editData['remarks'] ?? ''; ?></textarea>

</div>

<div class="form-btn">

<?php if($editData){ ?>

<button type="submit" name="update">
Update Progress
</button>

<?php } else { ?>

<button type="submit" name="add">
Add Progress
</button>

<?php } ?>

</div>

</form>

</div>

<!-- TABLE -->
<div class="table-box">

<h3>All Progress Records</h3>

<table class="table">

<tr>
<th>Project</th>
<th>Progress</th>
<th>Status</th>
<th>Remarks</th>
<th>Updated</th>
<th>Actions</th>
</tr>

<?php

$res = $conn->query("
SELECT pr.*, p.name
FROM progress pr
JOIN projects p ON pr.project_id = p.id
ORDER BY pr.updated_at DESC
");

while($row = $res->fetch_assoc()){
?>

<tr>

<td><?php echo $row['name']; ?></td>

<td>

<?php echo $row['progress_percent']; ?>%

<div class="progress-bar">

<div class="progress-fill"
style="width: <?php echo $row['progress_percent']; ?>%;"></div>

</div>

</td>

<td class="status-<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>

<td><?php echo $row['remarks']; ?></td>

<td><?php echo $row['updated_at']; ?></td>

<td class="actions">

<a class="action-btn edit-btn"
href="progress.php?edit=<?php echo $row['id']; ?>">
Edit
</a>

<a class="action-btn delete-btn"
href="progress.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this progress record?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</body>
</html>