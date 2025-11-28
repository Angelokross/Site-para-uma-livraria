<?php
require 'inc/db.php';
$title = 'Autores';
require 'inc/header.php';

// pagination
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$total = $pdo->query('SELECT COUNT(*) FROM autores')->fetchColumn();
$stmt = $pdo->prepare('SELECT * FROM autores ORDER BY id DESC LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$autores = $stmt->fetchAll();
$totalPages = ceil($total / $limit);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Autores</h1>
  <a class="btn btn-primary" href="autor_form.php">+ Novo Autor</a>
</div>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Nome</th><th>Nacionalidade</th><th>Ações</th></tr></thead>
  <tbody>
  <?php foreach($autores as $a): ?>
    <tr>
      <td><?= $a['id'] ?></td>
      <td><?= htmlspecialchars($a['nome']) ?></td>
      <td><?= htmlspecialchars($a['nacionalidade']) ?></td>
      <td>
        <a class="btn btn-sm btn-secondary" href="autor_form.php?id=<?= $a['id'] ?>">Editar</a>
        <a class="btn btn-sm btn-danger" href="autor_delete.php?id=<?= $a['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<!-- pagination -->
<nav>
  <ul class="pagination">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page-1 ?>">&laquo; Anterior</a></li>
    <?php else: ?>
      <li class="page-item disabled"><span class="page-link">&laquo; Anterior</span></li>
    <?php endif; ?>
    <?php for($p=1;$p<=$totalPages;$p++): ?>
      <li class="page-item <?= $p==$page? 'active':'' ?>"><a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a></li>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page+1 ?>">Próxima &raquo;</a></li>
    <?php else: ?>
      <li class="page-item disabled"><span class="page-link">Próxima &raquo;</span></li>
    <?php endif; ?>
  </ul>
</nav>

<?php require 'inc/footer.php'; ?>