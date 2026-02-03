<?php
include 'config.php';
$stmt=$conn->prepare("INSERT INTO orders(name,company,email,cart_data) VALUES (?,?,?,?)");
$stmt->bind_param("ssss",$_POST['name'],$_POST['company'],$_POST['email'],$_POST['cartData']);
$stmt->execute();
echo "Invoice Generated. Admin will contact you.";
?>