<?php
session_start();
include 'db.php';

/* =========================
   CURRENT DATE
========================= */
$currentDate = date("Y-m-d");

/* =========================
   INSERT TASK
========================= */
if(isset($_POST['add'])){

    $pid = $_POST['project_id'];

    $task = $_POST['task'];
    $date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    // DATE VALIDATION
    if($date < $currentDate){

        echo "<script>
                alert('Due Date cannot be a previous date');
                window.history.back();
              </script>";
        exit();
    }

    $conn->query("INSERT INTO tasks
    (project_id,task_name,due_date,priority,status)
    VALUES ('$pid','$task','$date','$priority','$status')");

    header("Location: tasks.php?success=1");
    exit();
}

/* =========================
   DELETE TASK
========================= */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $conn->query("DELETE FROM tasks WHERE id='$id'");

    header("Location: tasks.php?deleted=1");
    exit();
}

/* =========================
   FETCH TASK FOR EDIT
========================= */
$editData = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query("SELECT * FROM tasks WHERE id='$id'");
    $editData = $result->fetch_assoc();
}

/* =========================
   UPDATE TASK
========================= */
if(isset($_POST['update'])){

    $id = $_POST['id'];

    $pid = $_POST['project_id'];
    $task = $_POST['task'];
    $date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    // DATE VALIDATION
    if($date < $currentDate){

        echo "<script>
                alert('Due Date cannot be a previous date');
                window.history.back();
              </script>";
        exit();
    }

    $conn->query("UPDATE tasks SET
        project_id='$pid',
        task_name='$task',
        due_date='$date',
        priority='$priority',
        status='$status'
        WHERE id='$id'
    ");

    header("Location: tasks.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Tasks</title>

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

/* FORM BOX */
.form-box{

    padding:25px;
    border-radius:15px;
    margin-bottom:25px;

    background:rgba(80,50,30,0.35);
    backdrop-filter:blur(15px);

    border:1px solid rgba(255,140,0,0.3);
    box-shadow:0 10px 30px rgba(0,0,0,0.7);
}

/* TABLE BOX */
.table-box{

    padding:25px;
    border-radius:15px;

    background:rgba(40,25,15,0.6);
    backdrop-filter:blur(12px);

    border:1px solid rgba(255,140,0,0.25);
    box-shadow:0 10px 35px rgba(0,0,0,0.8);

    overflow-x:auto;
}

/* FORM ROW */
.form-row{
    display:flex;
    gap:20px;
    margin-bottom:18px;
}

/* FORM GROUP */
.form-group{
    flex:1;
}

/* LABEL */
.form-group label{
    display:block;
    margin-bottom:6px;
    font-weight:500;
}

/* INPUT */
.form-group input,
.form-group select{
    width:100%;
    padding:12px;

    border-radius:8px;
    border:none;
    outline:none;

    background:rgba(255,255,255,0.1);
    color:white;

    font-size:14px;
}

.form-group select option{
    color:black;
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
    font-size:15px;

    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.02);
    box-shadow:0 0 15px rgba(255,152,0,0.6);
}

/* TABLE */
.table{
    width:100%;
    border-collapse:collapse;
    min-width:950px;
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

/* ROW HOVER */
.table tr:hover{
    background:rgba(255,140,0,0.1);
}

/* BADGES */
.badge{
    padding:6px 12px;
    border-radius:20px;

    font-size:12px;
    font-weight:600;

    display:inline-block;
}

/* PRIORITY */
.priority-high{
    background:#e53935;
}

.priority-medium{
    background:#fb8c00;
}

.priority-low{
    background:#43a047;
}

/* STATUS */
.status-pending{
    background:#ff9800;
}

.status-completed{
    background:#4caf50;
}

/* DATE */
.date{
    color:#ddd;
    font-size:13px;
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
    margin-bottom:15px;
    font-weight:500;
}

/* RESPONSIVE */
@media(max-width:900px){

    .form-row{
        flex-direction:column;
    }

    .navbar{
        left:0;
        width:100%;
    }

    .content{
        margin-left:0;
        padding:100px 15px;
    }
}

</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="navbar">
    📝 Task Management
</div>

<div class="content">

<h2>Task Management</h2>

<?php

if(isset($_GET['success'])){
    echo "<p class='success'>Task Added Successfully!</p>";
}

if(isset($_GET['updated'])){
    echo "<p class='success'>Task Updated Successfully!</p>";
}

if(isset($_GET['deleted'])){
    echo "<p class='success'>Task Deleted Successfully!</p>";
}

?>

<!-- FORM -->
<div class="form-box">

<?php if($editData){ ?>
<h3>Edit Task</h3>
<?php } else { ?>
<h3>Add Task</h3>
<?php } ?>

<form method="POST">

<?php if($editData){ ?>
<input type="hidden" name="id"
value="<?php echo $editData['id']; ?>">
<?php } ?>

<!-- ROW 1 -->
<div class="form-row">

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
?>

>

<?php echo $row['name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label>Task Name</label>

<input type="text"
name="task"

value="<?php echo $editData['task_name'] ?? ''; ?>"

required>

</div>

<div class="form-group">
<label>Due Date</label>

<input type="date"
name="due_date"
min="<?php echo $currentDate; ?>"

value="<?php echo $editData['due_date'] ?? ''; ?>"

required>

</div>

</div>

<!-- ROW 2 -->
<div class="form-row">

<div class="form-group">

<label>Priority</label>

<select name="priority">

<option value="High"
<?php if(($editData['priority'] ?? '')=="High") echo "selected"; ?>>
High
</option>

<option value="Medium"
<?php if(($editData['priority'] ?? '')=="Medium") echo "selected"; ?>>
Medium
</option>

<option value="Low"
<?php if(($editData['priority'] ?? '')=="Low") echo "selected"; ?>>
Low
</option>

</select>

</div>

<div class="form-group">

<label>Status</label>

<select name="status">

<option value="Pending"
<?php if(($editData['status'] ?? '')=="Pending") echo "selected"; ?>>
Pending
</option>

<option value="Completed"
<?php if(($editData['status'] ?? '')=="Completed") echo "selected"; ?>>
Completed
</option>

</select>

</div>

<div class="form-group">
<label>&nbsp;</label>

<?php if($editData){ ?>

<button type="submit" name="update">
Update Task
</button>

<?php } else { ?>

<button type="submit" name="add">
Add Task
</button>

<?php } ?>

</div>

</div>

</form>

</div>

<!-- TABLE -->
<div class="table-box">

<h3>All Tasks</h3>

<table class="table">

<tr>
<th>Project</th>
<th>Task</th>
<th>Due Date</th>
<th>Priority</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php

$res = $conn->query("
SELECT t.*, p.name
FROM tasks t
JOIN projects p ON t.project_id = p.id
ORDER BY t.id DESC
");

while($row = $res->fetch_assoc()){

?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['task_name']; ?></td>

<td class="date">
<?php echo date("d M Y", strtotime($row['due_date'])); ?>
</td>

<td>

<span class="badge priority-<?php echo strtolower($row['priority']); ?>">

<?php echo $row['priority']; ?>

</span>

</td>

<td>

<span class="badge status-<?php echo strtolower($row['status']); ?>">

<?php echo $row['status']; ?>

</span>

</td>

<td class="actions">

<a class="action-btn edit-btn"
href="tasks.php?edit=<?php echo $row['id']; ?>">
Edit
</a>

<a class="action-btn delete-btn"
href="tasks.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this task?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>