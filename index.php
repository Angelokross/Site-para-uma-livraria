<?php
require 'inc/db.php';
$title = 'Dashboard - Livraria';
require 'inc/header.php';

// Stats
$totalAuthors = $pdo->query('SELECT COUNT(*) FROM autores')->fetchColumn();
$totalBooks = $pdo->query('SELECT COUNT(*) FROM livros')->fetchColumn();
$recentBooks = $pdo->query('SELECT l.titulo, l.ano, a.nome AS autor FROM livros l JOIN autores a ON l.autor_id=a.id ORDER BY l.criado_em DESC LIMIT 5')->fetchAll();
?>
<h1 class="mb-4">Dashboard</h1>
<div class="card-stats mb-4">
  <div class="card text-white bg-primary">
    <div class="card-body">
      <h5 class="card-title">Autores</h5>
      <p class="card-text display-6"><?= $totalAuthors ?></p>
    </div>
  </div>
  <div class="card text-white bg-success">
    <div class="card-body">
      <h5 class="card-title">Livros</h5>
      <p class="card-text display-6"><?= $totalBooks ?></p>
    </div>
  </div>
  <div class="card bg-light">
    <div class="card-body">
      <h5 class="card-title">Ações Rápidas</h5>
      <a class="btn btn-primary me-2" href="autor_form.php">Novo Autor</a>
      <a class="btn btn-success" href="livro_form.php">Novo Livro</a>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">Últimos livros adicionados</div>
  <div class="card-body p-0">
    <table class="table mb-0">
      <thead><tr><th>Título</th><th>Ano</th><th>Autor</th></tr></thead>
      <tbody>
        <?php if ($recentBooks): foreach($recentBooks as $rb): ?>
        <tr>
          <td><?= htmlspecialchars($rb['titulo']) ?></td>
          <td><?= htmlspecialchars($rb['ano']) ?></td>
          <td><?= htmlspecialchars($rb['autor']) ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="3">Nenhum livro ainda.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require 'inc/footer.php'; ?>