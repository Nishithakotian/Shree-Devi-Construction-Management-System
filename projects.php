<?php
session_start();
include 'db.php';

/* =========================
   ADD PROJECT
========================= */
if(isset($_POST['add'])){

    $name = $_POST['name'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $status = $_POST['status'];

    // DATE VALIDATION
    if($end < $start){

        echo "<script>
                alert('End Date cannot be earlier than Start Date');
                window.history.back();
              </script>";
        exit();
    }

    $conn->query("INSERT INTO projects
    (name,type,location,start_date,end_date,status)
    VALUES ('$name','$type','$location','$start','$end','$status')");

    header("Location: projects.php?success=1");
    exit();
}

/* =========================
   DELETE PROJECT
========================= */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    // DELETE RELATED RECORDS FIRST
    $conn->query("DELETE FROM tasks WHERE project_id='$id'");
    $conn->query("DELETE FROM budget WHERE project_id='$id'");
    $conn->query("DELETE FROM progress WHERE project_id='$id'");

    // NOW DELETE PROJECT
    $delete = $conn->query("DELETE FROM projects WHERE id='$id'");

    if($delete){

        header("Location: projects.php?deleted=1");
        exit();

    } else {

        echo "<script>
                alert('Project cannot be deleted');
                window.location='projects.php';
              </script>";
        exit();
    }
}

/* =========================
   FETCH PROJECT FOR EDIT
========================= */
$editData = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query("SELECT * FROM projects WHERE id='$id'");
    $editData = $result->fetch_assoc();
}

/* =========================
   UPDATE PROJECT
========================= */
if(isset($_POST['update'])){

    $id = $_POST['id'];

    $name = $_POST['name'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $status = $_POST['status'];

    // DATE VALIDATION
    if($end < $start){

        echo "<script>
                alert('End Date cannot be earlier than Start Date');
                window.history.back();
              </script>";
        exit();
    }

    $conn->query("UPDATE projects SET
        name='$name',
        type='$type',
        location='$location',
        start_date='$start',
        end_date='$end',
        status='$status'
        WHERE id='$id'
    ");

    header("Location: projects.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Projects</title>

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

    background:rgba(0,0,0,0.5);
    backdrop-filter:blur(10px);

    display:flex;
    align-items:center;
    padding:0 25px;

    color:#ffcc80;
    font-size:20px;
    font-weight:600;

    z-index:1000;
    box-shadow:0 5px 20px rgba(0,0,0,0.7);
}

/* CONTENT */
.content{
    margin-left:240px;
    padding:100px 30px 30px;
}

/* TITLE */
h2{
    color:#ffcc80;
    border-left:5px solid #ff9800;
    padding-left:12px;
    margin-bottom:20px;
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

/* FORM GRID */
form{
    display:grid;
    grid-template-columns:repeat(3,1fr);
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
.form-group select{
    width:100%;
    padding:12px;

    border:none;
    outline:none;
    border-radius:8px;

    background:rgba(255,255,255,0.1);
    color:white;

    font-size:14px;
}

.form-group select option{
    color:black;
}

/* BUTTON WRAPPER */
.form-btn{
    grid-column:1 / 4;
}

/* BUTTON */
button{
    width:100%;
    padding:13px;

    border:none;
    border-radius:8px;

    background:linear-gradient(135deg,#ff9800,#ffb74d);
    color:white;

    font-size:15px;
    font-weight:600;

    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.02);
}

/* TABLE */
.table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

.table th{
    background:rgba(255,140,0,0.25);
    padding:14px;

    color:#ffcc80;
    text-align:left;
    font-weight:600;
}

.table td{
    padding:14px;
    border-bottom:1px solid rgba(255,255,255,0.08);
    vertical-align:middle;
}

.table tr:hover{
    background:rgba(255,140,0,0.08);
}

/* STATUS */
.status-ongoing{
    color:#ffb74d;
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

/* ACTIONS */
.actions{
    display:flex;
    flex-direction:column;
    gap:8px;
    align-items:flex-start;
}

/* ACTION BUTTONS */
.action-btn{
    display:inline-block;

    min-width:75px;
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
    margin-bottom:15px;
    font-weight:500;
}

/* RESPONSIVE */
@media(max-width:1000px){

    form{
        grid-template-columns:repeat(2,1fr);
    }

    .form-btn{
        grid-column:1 / 3;
    }
}

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

    .form-btn{
        grid-column:auto;
    }
}

</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="navbar">
    🏗 Project Management
</div>

<div class="content">

<h2>Project Management</h2>

<?php
if(isset($_GET['success'])){
    echo "<p class='success'>Project added successfully!</p>";
}

if(isset($_GET['updated'])){
    echo "<p class='success'>Project updated successfully!</p>";
}

if(isset($_GET['deleted'])){
    echo "<p class='success'>Project deleted successfully!</p>";
}
?>

<div class="container">

<!-- FORM -->
<div class="form-box">

<?php if($editData){ ?>
<h3>Edit Project</h3>
<?php } else { ?>
<h3>Add Project</h3>
<?php } ?>

<form method="POST">

<?php if($editData){ ?>
<input type="hidden" name="id" value="<?php echo $editData['id']; ?>">
<?php } ?>

<div class="form-group">
<label>Project Name</label>
<input type="text" name="name"
value="<?php echo $editData['name'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Type</label>
<input type="text" name="type"
value="<?php echo $editData['type'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Location</label>
<input type="text" name="location"
value="<?php echo $editData['location'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Start Date</label>
<input type="date" name="start_date"
value="<?php echo $editData['start_date'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>End Date</label>
<input type="date" name="end_date"
value="<?php echo $editData['end_date'] ?? ''; ?>" required>
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

<option value="Pending"
<?php if(($editData['status'] ?? '')=="Pending") echo "selected"; ?>>
Pending
</option>

</select>

</div>

<div class="form-btn">

<?php if($editData){ ?>

<button type="submit" name="update">
Update Project
</button>

<?php } else { ?>

<button type="submit" name="add">
Add Project
</button>

<?php } ?>

</div>

</form>

</div>

<!-- TABLE -->
<div class="table-box">

<h3>All Projects</h3>

<table class="table">

<tr>
<th>Name</th>
<th>Type</th>
<th>Location</th>
<th>Start</th>
<th>End</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php

$res = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");

while($row = $res->fetch_assoc()){

?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['type']; ?></td>

<td><?php echo $row['location']; ?></td>

<td>
<?php echo date("d M Y", strtotime($row['start_date'])); ?>
</td>

<td>
<?php echo date("d M Y", strtotime($row['end_date'])); ?>
</td>

<td class="status-<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>

<td class="actions">

<a class="action-btn edit-btn"
href="projects.php?edit=<?php echo $row['id']; ?>">
Edit
</a>

<a class="action-btn delete-btn"
href="projects.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this project?')">
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