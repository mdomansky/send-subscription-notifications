<?php
/*
 * On daily basis the script gets users whose subscriptions expiring within 30 days and checks their emails for validity.
 * Having checked it updates field `checked=1` to avoid checking it again.
 * The field `valid` shows if the email is valid and the message would be delivered.
 */

require '_config.php';

$daysBeforeNotification = 30;

// grab user's with NOT confirmed and NOT checked emails withing the range of today and 30 days in future
$sql = "select * from `users` 
        where `confirmed` = 0 
           and `checked` = 0 
           and `subscribed` = 1 
           and `valid_till` between curdate() and curdate() + interval {$daysBeforeNotification} day 
        order by `valid_till` asc";
$result = mysqli_query($mysqli, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $check = checkEmail($row['email']);

    $sql = "update `users` set `checked` = 1, `valid` = 0 where `id` = {$row['id']}";
    if ($check) {
        $sql = "update `users` set `checked` = 1, `valid` = 1 where `id` = {$row['id']}";
    }

    if (!mysqli_query($mysqli, $sql)) {
        addLog("Сообщение ошибки: \n" . mysqli_error($mysqli));
    }
}


mysqli_close($mysqli);