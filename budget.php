# budget.php

```php
<?php
session_start();
include 'db.php';

/* =========================
   INSERT BUDGET
========================= */
if(isset($_POST['add'])){

    $pid = $_POST['project_id'];
    $total = $_POST['total_budget'];
    $spent = $_POST['spent_amount'];

    $remaining = $total - $spent;

    if($spent > $total){

        echo "<script>
                alert('Spent Amount cannot be greater than Total Budget');
                window.history.back();
              </script>";
        exit();
    }

    $conn->query("INSERT INTO budget
    (project_id,total_budget,spent_amount,remaining_amount)
    VALUES ('$pid','$total','$spent','$remaining')");

    header("Location: budget.php?success=1");
    exit();
}

/* =========================
   DELETE BUDGET
========================= */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $conn->query("DELETE FROM budget WHERE id='$id'");

    header("Location: budget.php?deleted=1");
    exit();
}

/* =========================
   FETCH BUDGET FOR EDIT
========================= */
$editData = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query("SELECT * FROM budget WHERE id='$id'");
    $editData = $result->fetch_assoc();
}

/* =========================
   UPDATE BUDGET
========================= */
if(isset($_POST['update'])){

    $id = $_POST['id'];

    $pid = $_POST['project_id'];
    $total = $_POST['total_budget'];
    $spent = $_POST['spent_amount'];

    $remaining = $total - $spent;

    if($spent > $total){

        echo "<script>
                alert('Spent Amount cannot be greater than Total Budget');
                window.history.back();
              </script>";
        exit();
    }

    $conn->query("UPDATE budget SET
        project_id='$pid',
        total_budget='$total',
        spent_amount='$spent',
        remaining_amount='$remaining'
        WHERE id='$id'
    ");

    header("Location: budget.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Budget Management</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background:url('cont.jpg') no-repeat center center/cover;
    color:white;
}

body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(30,15,5,0.85);
    z-index:-1;
}

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

.content{
    margin-left:240px;
    padding:110px 30px;
}

h2{
    color:#ffcc80;
    border-left:5px solid #ff9800;
    padding-left:10px;
}

.form-box{

    padding:25px;
    border-radius:15px;
    margin-bottom:25px;

    background:rgba(80,50,30,0.35);
    backdrop-filter:blur(15px);

    border:1px solid rgba(255,140,0,0.3);
    box-shadow:0 10px 30px rgba(0,0,0,0.7);
}

.table-box{

    padding:25px;
    border-radius:15px;

    background:rgba(40,25,15,0.6);
    backdrop-filter:blur(12px);

    border:1px solid rgba(255,140,0,0.25);
    box-shadow:0 10px 35px rgba(0,0,0,0.8);

    overflow-x:auto;
}

.form-row{
    display:flex;
    gap:20px;
    margin-bottom:18px;
}

.form-group{
    flex:1;
}

.form-group label{
    display:block;
    margin-bottom:6px;
    font-weight:500;
}

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

.table tr:hover{
    background:rgba(255,140,0,0.1);
}

.amount{
    color:#81c784;
    font-weight:600;
}

.remaining{
    color:#4dd0e1;
    font-weight:600;
}

.actions{
    display:flex;
    flex-direction:column;
    gap:8px;
    align-items:flex-start;
}

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

.edit-btn{
    background:#42a5f5;
}

.delete-btn{
    background:#ef5350;
}

.success{
    color:#81c784;
    margin-bottom:15px;
    font-weight:500;
}

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
    💰 Budget Management
</div>

<div class="content">

<h2>Budget Management</h2>

<?php

if(isset($_GET['success'])){
    echo "<p class='success'>Budget Added Successfully!</p>";
}

if(isset($_GET['updated'])){
    echo "<p class='success'>Budget Updated Successfully!</p>";
}

if(isset($_GET['deleted'])){
    echo "<p class='success'>Budget Deleted Successfully!</p>";
}

?>

<div class="form-box">

<?php if($editData){ ?>
<h3>Edit Budget</h3>
<?php } else { ?>
<h3>Add Budget</h3>
<?php } ?>

<form method="POST">

<?php if($editData){ ?>
<input type="hidden" name="id"
value="<?php echo $editData['id']; ?>">
<?php } ?>

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
<label>Total Budget</label>

<input type="number"
name="total_budget"
step="0.01"

value="<?php echo $editData['total_budget'] ?? ''; ?>"

required>

</div>

<div class="form-group">
<label>Spent Amount</label>

<input type="number"
name="spent_amount"
step="0.01"

value="<?php echo $editData['spent_amount'] ?? ''; ?>"

required>

</div>

</div>

<div class="form-row">

<div class="form-group">
<label>&nbsp;</label>

<?php if($editData){ ?>

<button type="submit" name="update">
Update Budget
</button>

<?php } else { ?>

<button type="submit" name="add">
Add Budget
</button>

<?php } ?>

</div>

</div>

</form>

</div>

<div class="table-box">

<h3>All Budgets</h3>

<table class="table">

<tr>
<th>Project</th>
<th>Total Budget</th>
<th>Spent Amount</th>
<th>Remaining Amount</th>
<th>Actions</th>
</tr>

<?php

$res = $conn->query("
SELECT b.*, p.name
FROM budget b
JOIN projects p ON b.project_id = p.id
ORDER BY b.id DESC
");

while($row = $res->fetch_assoc()){

?>

<tr>

<td><?php echo $row['name']; ?></td>

<td class="amount">
₹ <?php echo number_format($row['total_budget'],2); ?>
</td>

<td>
₹ <?php echo number_format($row['spent_amount'],2); ?>
</td>

<td class="remaining">
₹ <?php echo number_format($row['remaining_amount'],2); ?>
</td>

<td class="actions">

<a class="action-btn edit-btn"
href="budget.php?edit=<?php echo $row['id']; ?>">
Edit
</a>

<a class="action-btn delete-btn"
href="budget.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this budget?')">
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
```
