<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
include __DIR__ . '/templates/header.php';
?>
<h3>Üdv, <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['role']) ?>)</h3>
<nav>
  <a href="dashboard.php" class="btn btn-secondary active-btn">Főoldal</a>
  <?php if ($user['role'] === 'Admin'): ?>
    <a href="admin.php" class="btn btn-secondary active-btn">Felhasználók</a>
  <?php endif; ?>
  <?php if ($user['role'] === 'Felmérő'): ?>
    <a href="create_project.php" class="btn btn-secondary active-btn">Projekt létrehozása</a>
  <?php endif; ?>
  <a href="logout.php" class="btn btn-danger">Kilépés</a>
</nav>
<?php
include __DIR__ . '/templates/footer.php';
