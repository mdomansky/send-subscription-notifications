<?php
/*
 * Unsubscribe user from notifications
 */


require '_config.php';

$userId = decrypt($_GET['hash']);
if ($userId) {
    $sql = "update `users` set `subscribed` = 0 where `id` = {$userId}";
    if (!mysqli_query($mysqli, $sql)) {
        addLog("Сообщение ошибки: %s\n" . mysqli_error($mysqli));
    }
}

mysqli_close($mysqli);