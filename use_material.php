<?php
$conn = new mysqli("localhost", "root", "", "window_inventory");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$project_id = $_POST['project_id'];
$material_id = $_POST['material_id'];
$quantity_used = $_POST['quantity_used'];

// Provera trenutnog stanja
$stmt = $conn->prepare("SELECT quantity, min_quantity, name FROM materials WHERE id = ?");
$stmt->bind_param("i", $material_id);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();
$stmt->close();

if(!$material){
    echo "Materijal ne postoji.";
    exit;
}

if($material['quantity'] < $quantity_used){
    echo "Nema dovoljno materijala!";
    exit;
}

// Smanjujemo količinu
$new_quantity = $material['quantity'] - $quantity_used;
$stmt = $conn->prepare("UPDATE materials SET quantity = ? WHERE id = ?");
$stmt->bind_param("di", $new_quantity, $material_id);
$stmt->execute();
$stmt->close();

// Evidencija u projektu
$stmt = $conn->prepare("INSERT INTO project_materials (project_id, material_id, quantity_used) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $project_id, $material_id, $quantity_used);
$stmt->execute();
$stmt->close();

if($new_quantity < $material['min_quantity']){
    echo "<span class='warning'>Upozorenje: Materijal '{$material['name']}' pao ispod minimuma!</span>";
}else{
    echo "Materijal uspešno potrošen.";
}

$conn->close();
?>
