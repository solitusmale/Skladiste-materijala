<?php
$host = "localhost";
$db = "window_inventory";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dodavanje materijala
function addMaterial($mat_name, $mat_type, $manufacturer, $quantity, $min_quantity, $unit) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO materials (mat_name, mat_type, manufacturer, quantity, min_quantity, unit) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdds", $mat_name, $mat_type, $manufacturer, $quantity, $min_quantity, $unit);
    $stmt->execute();
    $stmt->close();
}

// Smanjenje materijala prilikom projekta
function useMaterial($project_id, $material_id, $quantity_used) {
    global $conn;

    // Provera trenutnog stanja
    $stmt = $conn->prepare("SELECT quantity, min_quantity, name FROM materials WHERE id = ?");
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();
    $stmt->close();

    if (!$material) return "Materijal ne postoji.";

    if ($material['quantity'] < $quantity_used) return "Nema dovoljno materijala!";

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

    // Obaveštenje ako je ispod minimuma
    if ($new_quantity < $material['min_quantity']) {
        echo "Upozorenje: Materijal '{$material['name']}' pao ispod minimuma!\n";
    }

    return "Materijal uspešno zabeležen.";
}

// Primer upotrebe
// addMaterial("PVC profil 60mm", "Profil", "Kumplast", 100, 20, "kom");
// echo useMaterial(1, 1, 10);

$conn->close();
?>
