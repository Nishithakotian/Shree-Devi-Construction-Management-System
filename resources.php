<?php
session_start();
include 'db.php';

/* =========================
   ADD RESOURCE
========================= */
if(isset($_POST['add'])){

    $name = $_POST['name'];
    $type = $_POST['type'];
    $qty = $_POST['quantity'];
    $cost = $_POST['cost'];
    $supplier = $_POST['supplier'];
    $status = $_POST['status'];

    $conn->query("INSERT INTO resources
    (name,type,quantity,cost_per_unit,supplier,status)
    VALUES ('$name','$type','$qty','$cost','$supplier','$status')");

    header("Location: resources.php?success=1");
    exit();
}

/* =========================
   DELETE RESOURCE
========================= */
if(isset($_GET['delete'])){

    $id = $_GET['delete'];

    $conn->query("DELETE FROM resources WHERE id='$id'");

    header("Location: resources.php?deleted=1");
    exit();
}

/* =========================
   FETCH RESOURCE FOR EDIT
========================= */
$editData = null;

if(isset($_GET['edit'])){

    $id = $_GET['edit'];

    $result = $conn->query("SELECT * FROM resources WHERE id='$id'");
    $editData = $result->fetch_assoc();
}

/* =========================
   UPDATE RESOURCE
========================= */
if(isset($_POST['update'])){

    $id = $_POST['id'];

    $name = $_POST['name'];
    $type = $_POST['type'];
    $qty = $_POST['quantity'];
    $cost = $_POST['cost'];
    $supplier = $_POST['supplier'];
    $status = $_POST['status'];

    $conn->query("UPDATE resources SET
        name='$name',
        type='$type',
        quantity='$qty',
        cost_per_unit='$cost',
        supplier='$supplier',
        status='$status'
        WHERE id='$id'
    ");

    header("Location: resources.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Resources</title>

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

/* BUTTON FULL WIDTH */
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
    min-width:850px;
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

/* STATUS COLORS */
.status-available{
    color:#66bb6a;
    font-weight:bold;
}

.status-used{
    color:#ffa726;
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
    🧱 Resource Management
</div>

<div class="content">

<h2>Resource Management</h2>

<?php
if(isset($_GET['success'])){
    echo "<p class='success'>Resource Added Successfully!</p>";
}

if(isset($_GET['updated'])){
    echo "<p class='success'>Resource Updated Successfully!</p>";
}

if(isset($_GET['deleted'])){
    echo "<p class='success'>Resource Deleted Successfully!</p>";
}
?>

<div class="container">

<!-- FORM -->
<div class="form-box">

<?php if($editData){ ?>
<h3>Edit Resource</h3>
<?php } else { ?>
<h3>Add Resource</h3>
<?php } ?>

<form method="POST">

<?php if($editData){ ?>
<input type="hidden" name="id"
value="<?php echo $editData['id']; ?>">
<?php } ?>

<div class="form-group">
<label>Name</label>
<input type="text" name="name"
value="<?php echo $editData['name'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Type</label>
<input type="text" name="type"
value="<?php echo $editData['type'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Quantity</label>
<input type="number" name="quantity"
value="<?php echo $editData['quantity'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Cost</label>
<input type="number" name="cost"
value="<?php echo $editData['cost_per_unit'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Supplier</label>
<input type="text" name="supplier"
value="<?php echo $editData['supplier'] ?? ''; ?>" required>
</div>

<div class="form-group">
<label>Status</label>

<select name="status">

<option value="Available"
<?php if(($editData['status'] ?? '')=="Available") echo "selected"; ?>>
Available
</option>

<option value="Used"
<?php if(($editData['status'] ?? '')=="Used") echo "selected"; ?>>
Used
</option>

</select>

</div>

<div class="form-btn">

<?php if($editData){ ?>

<button type="submit" name="update">
Update Resource
</button>

<?php } else { ?>

<button type="submit" name="add">
Add Resource
</button>

<?php } ?>

</div>

</form>

</div>

<!-- TABLE -->
<div class="table-box">

<h3>All Resources</h3>

<table class="table">

<tr>
<th>Name</th>
<th>Type</th>
<th>Qty</th>
<th>Cost</th>
<th>Supplier</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM resources ORDER BY created_at DESC");

while($row = $res->fetch_assoc()){
?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['type']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>₹ <?php echo $row['cost_per_unit']; ?></td>

<td><?php echo $row['supplier']; ?></td>

<td class="status-<?php echo strtolower($row['status']); ?>">
<?php echo $row['status']; ?>
</td>

<td class="actions">

<a class="action-btn edit-btn"
href="resources.php?edit=<?php echo $row['id']; ?>">
Edit
</a>

<a class="action-btn delete-btn"
href="resources.php?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this resource?')">
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