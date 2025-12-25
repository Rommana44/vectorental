<?php
require_once 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['reservation_id'])) {
    header("Location: my-reservations.php");
    exit();
}
$reservation_id = $_POST['reservation_id'];
$user_id = $_SESSION['user_id'];
// Check reservation ownership
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE reservation_id = ? AND customer_id = ?");
$stmt->execute([$reservation_id, $user_id]);
$reservation = $stmt->fetch();
if (!$reservation) {
    header("Location: my-reservations.php?msg=notfound");
    exit();
}
// Update reservation status to Cancelled
$stmt = $pdo->prepare("UPDATE reservations SET reservation_status = 'Cancelled' WHERE reservation_id = ?");
$stmt->execute([$reservation_id]);
// Delete payment for this reservation
$stmt = $pdo->prepare("DELETE FROM payments WHERE reservation_id = ?");
$stmt->execute([$reservation_id]);
header("Location: my-reservations.php?msg=cancelled");
exit();
