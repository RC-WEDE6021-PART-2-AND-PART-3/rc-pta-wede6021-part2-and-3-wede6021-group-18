<?php
// Admin dashboard - Manage users (CRUD)

include 'DBConn.php';

// =========================
// ADD USER
// =========================
if (isset($_POST['addUser'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $conn->query("INSERT INTO tblUser (name,email,password,status)
    VALUES ('$name','$email','$password','Approved')");
}

// =========================
// DELETE USER
// =========================
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM tblUser WHERE id=$id");
}

// =========================
// LOAD USER FOR EDIT
// =========================
$editUser = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM tblUser WHERE id=$id");
    $editUser = $result->fetch_assoc();
}

// =========================
// UPDATE USER
// =========================
if (isset($_POST['updateUser'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $conn->query("UPDATE tblUser 
                  SET name='$name', email='$email', status='$status' 
                  WHERE id=$id");
}

// =========================
// APPROVE USER
// =========================
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];
    $conn->query("UPDATE tblUser SET status='Approved' WHERE id=$id");
}

// FETCH USERS
$result = $conn->query("SELECT * FROM tblUser");
?>

<link rel="stylesheet" href="css/style.css">
<?php include 'navbar.php'; ?>

<div class="container" style="max-width:1000px;">
<div class="card">

<h2 class="title">Admin Dashboard - Manage Users</h2>

<!-- ========================= -->
<!-- ADD / UPDATE FORM -->
<!-- ========================= -->
<form method="POST">

    <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">

    <label>Name</label>
    <input type="text" name="name"
           value="<?= $editUser['name'] ?? '' ?>" required>

    <label>Email</label>
    <input type="email" name="email"
           value="<?= $editUser['email'] ?? '' ?>" required>

    <?php if (!$editUser): ?>
        <!-- Only show password when adding -->
        <label>Password</label>
        <input type="password" name="password" required>
    <?php endif; ?>

    <label>Status</label>
    <select name="status">
        <option value="Pending"
            <?= (isset($editUser['status']) && $editUser['status']=="Pending") ? "selected" : "" ?>>
            Pending
        </option>
        <option value="Approved"
            <?= (isset($editUser['status']) && $editUser['status']=="Approved") ? "selected" : "" ?>>
            Approved
        </option>
    </select>

    <?php if ($editUser): ?>
        <button type="submit" name="updateUser">Update User</button>
    <?php else: ?>
        <button type="submit" name="addUser">Add User</button>
    <?php endif; ?>

</form>

<hr style="margin:20px 0;">

<!-- ========================= -->
<!-- USER TABLE -->
<!-- ========================= -->
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['status'] ?></td>
<td>
    <a href="?edit=<?= $row['id'] ?>">Edit</a> |
    <a href="?approve=<?= $row['id'] ?>">Approve</a> |
    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</div>
</div>