<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Felmérő') {
    header('Location: index.php');
    exit;
}
$db = require __DIR__ . '/db.php';
$workTypes = ['Padlásfödém szigetelés','Fűtéskorszerűsítés','Nyílászáró csere','Homlokzat szigetelés'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $land = $_POST['land'];
    $contract_name = $_POST['contract_name'];
    $contract_address = $_POST['contract_address'];
    $contract_tax = $_POST['contract_tax'];
    $contract_id = $_POST['contract_id'];
    $contract_phone = $_POST['contract_phone'];
    $contract_email = $_POST['contract_email'];
    $work_type = $_POST['work_type'];
    $db->beginTransaction();
    $stmt = $db->prepare('INSERT INTO properties (address, land_registry) VALUES (?, ?)');
    $stmt->execute([$address, $land]);
    $property_id = $db->lastInsertId();
    $stmt = $db->prepare('INSERT INTO contractors (name, address, tax_id, id_card, phone, email) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$contract_name,$contract_address,$contract_tax,$contract_id,$contract_phone,$contract_email]);
    $contractor_id = $db->lastInsertId();
    $identifier = date('Ymd') . '-' . strtoupper(substr($contract_name,0,2)) . '-' . mb_substr(str_replace('é','e', $work_type),0,3);
    $stmt = $db->prepare('INSERT INTO projects (identifier, work_type, property_id, contractor_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$identifier,$work_type,$property_id,$contractor_id]);
    $db->commit();
    $msg = "Projekt létrehozva azonosító: $identifier";
}
include __DIR__ . '/templates/header.php';
?>
<h3>Új projekt</h3>
<?php if (!empty($msg)): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
 <h5>Ingatlan</h5>
 <div class="mb-2"><input name="address" class="form-control" placeholder="Cím" required></div>
 <div class="mb-2"><input name="land" class="form-control" placeholder="Helyrajzi szám" required></div>
 <h5>Szerződő fél</h5>
 <div class="mb-2"><input name="contract_name" class="form-control" placeholder="Név" required></div>
 <div class="mb-2"><input name="contract_address" class="form-control" placeholder="Lakcím" required></div>
 <div class="mb-2"><input name="contract_tax" class="form-control" placeholder="Adószám" required></div>
 <div class="mb-2"><input name="contract_id" class="form-control" placeholder="Személyi igazolvány" required></div>
 <div class="mb-2"><input name="contract_phone" class="form-control" placeholder="Telefonszám" required></div>
 <div class="mb-2"><input name="contract_email" type="email" class="form-control" placeholder="Email"></div>
 <div class="mb-2">
  <select name="work_type" class="form-select" required>
   <?php foreach ($workTypes as $t): ?>
   <option value="<?= $t ?>"><?= $t ?></option>
   <?php endforeach; ?>
  </select>
 </div>
 <button type="submit" class="btn btn-primary active-btn">Mentés</button>
</form>
<?php
include __DIR__ . '/templates/footer.php';
