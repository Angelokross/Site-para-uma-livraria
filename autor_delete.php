<?php
require 'inc/db.php';
$id = $_GET['id'] ?? null;
if ($id) {
  $stmt = $pdo->prepare('DELETE FROM autores WHERE id = ?');
  $stmt->execute([$id]);
}
header('Location: autores.php'); exit;
?>