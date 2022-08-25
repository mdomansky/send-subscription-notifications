<?php
/*
 * On daily basis the script gets users whose subscriptions expiring within 30 days and checks their emails for validity.
 * Having checked it updates field `checked=1` to avoid checking it again.
 * The field `valid` shows if the email is valid and the message would be delivered.
 */

require '_config.php';


// grab user's with NOT confirmed and NOT checked emails withing the range of today and 30 days in future
$from = time();
$to = $from + 60 * 60 * 24 * 30;
$sql = "select * from `users` where `confirmed` = 0 and `checked` = 0 and `subscribed` = 1 and `validts` between {$from} and {$to} order by `validts` asc";
$result = mysqli_query($mysqli, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $check = checkEmail($row['email']);

    $sql = "update `users` set `checked` = 1, `valid` = 0 where `id` = {$row['id']}";
    if ($check) {
        $sql = "update `users` set `checked` = 1, `valid` = 1 where `id` = {$row['id']}";
    }

    if (!mysqli_query($mysqli, $sql)) {
        addLog("Сообщение ошибки: %s\n" . mysqli_error($mysqli));
    }
}


mysqli_close($mysqli);