<?php
include 'DBConn.php';
$result=$conn->query("SELECT * FROM tblClothes");
?>

<link rel="stylesheet" href="css/style.css">
<?php include 'navbar.php'; ?>

<div class="container">
<div class="card">
<h2>Clothes</h2>

<table>
<tr>
<th>Image</th>
<th>Name</th>
<th>Description</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><img src="images/<?= $row['image'] ?>" width="80"></td>
<td><?= $row['itemName'] ?></td>
<td><?= $row['description'] ?></td>
<td>R<?= $row['price'] ?></td>
<td>
<button onclick="alert('Price: R<?= $row['price'] ?>')">
Add to Cart
</button>
</td>
</tr>
<?php endwhile; ?>

</table>
</div>
</div>