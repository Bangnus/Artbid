<?php

$roleId = $_SESSION['user']['role_id'] ?? null;

if (!isset($_SESSION['user']) || !in_array($roleId, [2, 3])) {
    header("Location: home");
    exit;
}
