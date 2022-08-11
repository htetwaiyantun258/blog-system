<?php

require "../config/config.php";

$stmt = $pdo->prepare("DELETE FROM post WHERE id=". $_GET["id"]);
$stmt->execute();

header("Location: index.php");