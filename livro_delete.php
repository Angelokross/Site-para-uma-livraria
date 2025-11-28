<?php
require 'inc/db.php';
$id = $_GET['id'] ?? null;
if ($id) {
  $stmt = $pdo->prepare('DELETE FROM livros WHERE id = ?');
  $stmt->execute([$id]);
}
header('Location: livros.php'); exit;
?>