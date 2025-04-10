<?php
// /includes/db.php

$host = 'db'; // name of service from docker-compose.yml
$dbname = 'averweb';
$user = 'aver';
$pass = 'averpass';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  header('Content-Type: application/json');
  echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
  exit;
}
