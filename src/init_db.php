<?php
$db = require __DIR__ . '/db.php';

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    email TEXT UNIQUE,
    password TEXT,
    role TEXT,
    active INTEGER DEFAULT 1
);");

$db->exec("CREATE TABLE IF NOT EXISTS projects (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    identifier TEXT,
    work_type TEXT,
    property_id INTEGER,
    contractor_id INTEGER,
    FOREIGN KEY(property_id) REFERENCES properties(id),
    FOREIGN KEY(contractor_id) REFERENCES contractors(id)
);");

$db->exec("CREATE TABLE IF NOT EXISTS properties (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    address TEXT,
    land_registry TEXT
);");

$db->exec("CREATE TABLE IF NOT EXISTS contractors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    address TEXT,
    tax_id TEXT,
    id_card TEXT,
    phone TEXT,
    email TEXT,
    signature TEXT
);");

$db->exec("CREATE TABLE IF NOT EXISTS owners (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    property_id INTEGER,
    name TEXT,
    address TEXT,
    id_card TEXT,
    signature TEXT,
    FOREIGN KEY(property_id) REFERENCES properties(id)
);");

$adminEmail = 'admin@example.com';
$exists = $db->query("SELECT id FROM users WHERE email = '".$adminEmail."'")->fetch();
if (!$exists) {
    $password = password_hash('admin', PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute(['Admin', $adminEmail, $password, 'Admin']);
}

echo "Database initialized. Admin login: admin@example.com / admin\n";
