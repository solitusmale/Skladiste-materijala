<?php
// get_materials.php
$conn = new mysqli("localhost", "root", "", "window_inventory");
if ($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$sql = "SELECT * FROM materials";
$result = $conn->query($sql);

$materials = [];
while($row = $result->fetch_assoc()) {
    $materials[] = $row;
}

header('Content-Type: application/json');
echo json_encode($materials);

$conn->close();
?>
