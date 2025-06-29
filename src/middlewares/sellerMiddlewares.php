<?php


if (!isset($_SESSION['user']) || $_SESSION['user']['verifyemail'] !== true) {
    header("Location: home");
    exit;
}
