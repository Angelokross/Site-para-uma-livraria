<?php
require 'inc/db.php';
$title = 'Formulário Autor';

$id = $_GET['id'] ?? null;
$nome = '';
$nacionalidade = '';
if ($id) {
  $stmt = $pdo->prepare('SELECT * FROM autores WHERE id = ?');
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  if ($row) { $nome = $row['nome']; $nacionalidade = $row['nacionalidade']; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = $_POST['nome'] ?? '';
  $nac = $_POST['nacionalidade'] ?? '';
  if (empty($_POST['id'])) {
    $stmt = $pdo->prepare('INSERT INTO autores (nome, nacionalidade) VALUES (?,?)');
    $stmt->execute([$nome, $nac]);
  } else {
    $stmt = $pdo->prepare('UPDATE autores SET nome=?, nacionalidade=? WHERE id=?');
    $stmt->execute([$nome, $nac, $_POST['id']]);
  }
  header('Location: autores.php'); exit;
}

require 'inc/header.php';
?>
<h1><?= $id ? 'Editar' : 'Novo' ?> Autor</h1>
<form method="post">
  <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
  <div class="mb-3">
    <label class="form-label">Nome</label>
    <input class="form-control" name="nome" required value="<?= htmlspecialchars($nome) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Nacionalidade</label>
    <input class="form-control" name="nacionalidade" value="<?= htmlspecialchars($nacionalidade) ?>">
  </div>
  <button class="btn btn-primary">Salvar</button>
  <a class="btn btn-secondary" href="autores.php">Cancelar</a>
</form>
<?php require 'inc/footer.php'; ?>