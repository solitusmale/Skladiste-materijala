<?php
$conn = new mysqli("localhost", "root", "", "window_inventory");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$mat_name = $_POST['mat_name'];
$mat_type = $_POST['mat_type'];
$manufacturer = $_POST['manufacturer'];
$quantity = $_POST['quantity'];
$min_quantity = $_POST['min_quantity'];
$unit = $_POST['unit'];

$stmt = $conn->prepare("INSERT INTO materials (mat_name, mat_type, manufacturer, quantity, min_quantity, unit) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdds", $mat_name, $mat_type, $manufacturer, $quantity, $min_quantity, $unit);
if($stmt->execute()){
    echo "Materijal uspešno dodat!";
}else{
    echo "Greška: ".$conn->error;
}
$stmt->close();
$conn->close();
?>
