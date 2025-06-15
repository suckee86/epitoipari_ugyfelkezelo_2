<?php
session_start();
$config = require __DIR__ . '/config.php';

$db = require __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $db->prepare('SELECT * FROM users WHERE email = ? AND active = 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'role' => $user['role'], 'name' => $user['name']];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Hibás bejelentkezés';
    }
}
include __DIR__ . '/templates/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <h2>Belépés</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Jelszó" required>
      </div>
      <button type="submit" class="btn btn-primary active-btn">Belépés</button>
    </form>
  </div>
</div>
<?php
include __DIR__ . '/templates/footer.php';
