<?php

session_start();
if (
    !isset($_SESSION['user']) ||
    (
        $_SESSION['user']['role'] !== 'seller' &&
        $_SESSION['user']['role'] !== 'admin'
    )
) {
    header("Location: home");
    exit;
}
