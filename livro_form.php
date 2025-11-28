<?php
require 'inc/db.php';
$title = 'Formulário Livro';

$id = $_GET['id'] ?? null;
$titulo = '';
$ano = '';
$author_id = '';

// obter lista de autores para select
$autRes = $pdo->query('SELECT id, nome FROM autores ORDER BY nome');
$autoresList = $autRes->fetchAll();

if ($id) {
  $stmt = $pdo->prepare('SELECT * FROM livros WHERE id = ?');
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  if ($row) { $titulo = $row['titulo']; $ano = $row['ano']; $author_id = $row['autor_id']; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'] ?? '';
  $ano = $_POST['ano'] ?? null;
  if ($ano === '') $ano = null;
  $author_id = $_POST['autor_id'] ?? '';
  if (empty($_POST['id'])) {
    $stmt = $pdo->prepare('INSERT INTO livros (titulo, ano, autor_id) VALUES (?,?,?)');
    $stmt->execute([$titulo, $ano, $author_id]);
  } else {
    $stmt = $pdo->prepare('UPDATE livros SET titulo=?, ano=?, autor_id=? WHERE id=?');
    $stmt->execute([$titulo, $ano, $author_id, $_POST['id']]);
  }
  header('Location: livros.php'); exit;
}

require 'inc/header.php';
?>
<h1><?= $id ? 'Editar' : 'Novo' ?> Livro</h1>
<form method="post">
  <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
  <div class="mb-3">
    <label class="form-label">Título</label>
    <input class="form-control" name="titulo" required value="<?= htmlspecialchars($titulo) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Ano</label>
    <input class="form-control" name="ano" value="<?= htmlspecialchars($ano) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Autor</label>
    <select class="form-select" name="autor_id" required>
      <option value="">-- selecione --</option>
      <?php foreach($autoresList as $a): ?>
        <option value="<?= $a['id'] ?>" <?= $a['id']==$author_id? 'selected':''?>><?= htmlspecialchars($a['nome']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button class="btn btn-primary">Salvar</button>
  <a class="btn btn-secondary" href="livros.php">Cancelar</a>
</form>
<?php require 'inc/footer.php'; ?>