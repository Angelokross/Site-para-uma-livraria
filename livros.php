<?php
require 'inc/db.php';
$title = 'Livros';
require 'inc/header.php';

// pagination
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q !== '') {
  $countStmt = $pdo->prepare('SELECT COUNT(*) FROM livros l JOIN autores a ON l.autor_id=a.id WHERE l.titulo LIKE ?');
  $countStmt->execute(['%'.$q.'%']);
  $total = $countStmt->fetchColumn();
  $stmt = $pdo->prepare('SELECT l.*, a.nome AS autor_nome FROM livros l JOIN autores a ON l.autor_id = a.id WHERE l.titulo LIKE ? ORDER BY l.id DESC LIMIT :limit OFFSET :offset');
  $stmt->bindValue(1, '%'.$q.'%');
  $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
  $stmt->execute();
  $livros = $stmt->fetchAll();
} else {
  $total = $pdo->query('SELECT COUNT(*) FROM livros')->fetchColumn();
  $stmt = $pdo->prepare('SELECT l.*, a.nome AS autor_nome FROM livros l JOIN autores a ON l.autor_id = a.id ORDER BY l.id DESC LIMIT :limit OFFSET :offset');
  $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
  $stmt->execute();
  $livros = $stmt->fetchAll();
}
$totalPages = ceil($total / $limit);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Livros</h1>
  <a class="btn btn-primary" href="livro_form.php">+ Novo Livro</a>
</div>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Título</th><th>Ano</th><th>Autor</th><th>Ações</th></tr></thead>
  <tbody>
  <?php foreach($livros as $l): ?>
    <tr>
      <td><?= $l['id'] ?></td>
      <td><?= htmlspecialchars($l['titulo']) ?></td>
      <td><?= $l['ano'] ?></td>
      <td><?= htmlspecialchars($l['autor_nome']) ?></td>
      <td>
        <a class="btn btn-sm btn-secondary" href="livro_form.php?id=<?= $l['id'] ?>">Editar</a>
        <a class="btn btn-sm btn-danger" href="livro_delete.php?id=<?= $l['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<!-- pagination (keep q param if present) -->
<nav>
  <ul class="pagination">
    <?php $baseUrl = 'livros.php' . ($q !== '' ? '?q=' . urlencode($q) . '&' : '?'); ?>
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>page=<?= $page-1 ?>">&laquo; Anterior</a></li>
    <?php else: ?>
      <li class="page-item disabled"><span class="page-link">&laquo; Anterior</span></li>
    <?php endif; ?>
    <?php for($p=1;$p<=$totalPages;$p++): ?>
      <li class="page-item <?= $p==$page? 'active':'' ?>"><a class="page-link" href="<?= $baseUrl ?>page=<?= $p ?>"><?= $p ?></a></li>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <li class="page-item"><a class="page-link" href="<?= $baseUrl ?>page=<?= $page+1 ?>">Próxima &raquo;</a></li>
    <?php else: ?>
      <li class="page-item disabled"><span class="page-link">Próxima &raquo;</span></li>
    <?php endif; ?>
  </ul>
</nav>

<?php require 'inc/footer.php'; ?>