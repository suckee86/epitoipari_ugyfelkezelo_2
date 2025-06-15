<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Admin') {
    header('Location: index.php');
    exit;
}
$db = require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $password, $role]);
}

$users = $db->query('SELECT id, name, email, role, active FROM users')->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/templates/header.php';
?>
<h3>Felhasználók kezelése</h3>
<table class="table table-bordered">
<tr><th>Név</th><th>Email</th><th>Szerep</th><th>Aktív</th></tr>
<?php foreach ($users as $u): ?>
<tr>
 <td><?= htmlspecialchars($u['name']) ?></td>
 <td><?= htmlspecialchars($u['email']) ?></td>
 <td><?= htmlspecialchars($u['role']) ?></td>
 <td><?= $u['active'] ? 'Igen' : 'Nem' ?></td>
</tr>
<?php endforeach; ?>
</table>
<h4>Új felhasználó</h4>
<form method="post">
  <div class="mb-2"><input name="name" class="form-control" placeholder="Név" required></div>
  <div class="mb-2"><input name="email" type="email" class="form-control" placeholder="Email" required></div>
  <div class="mb-2">
    <select name="role" class="form-select" required>
      <option value="Felmérő">Felmérő</option>
      <option value="Kivitelező">Kivitelező</option>
      <option value="Ellenőrző">Ellenőrző</option>
      <option value="Auditor">Auditor</option>
      <option value="Admin">Admin</option>
    </select>
  </div>
  <div class="mb-2"><input name="password" type="text" class="form-control" placeholder="Jelszó" required></div>
  <button type="submit" class="btn btn-primary active-btn">Mentés</button>
</form>
<?php
include __DIR__ . '/templates/footer.php';
