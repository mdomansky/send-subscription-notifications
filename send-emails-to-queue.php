<?php
/*
 * On daily basis the script gets users whose subscriptions expiring in 3 days,
 * prepare all data for the sending and puts the task to the `queue_emails` table.
 * It uses `hash` property (it is encrypted with a key and can be decrypted) to give
 * the link for unsubscription.
 */


require '_config.php';

$daysBeforeNotification = 3;

// grab user's with confirmed or checked and valid emails whose subscription expires in 3 days
$result = getUsersForSending($mysqli, $daysBeforeNotification);
while ($row = mysqli_fetch_assoc($result)) {
    $hash = getHashByUserId($row['id']);
    $subject = getMessageSubject();
    $body = addslashes(getMessageBody($row['username'], $row['id'], $hash));

    if (!addTaskToQueue($mysqli, EMAIL_FROM, $row['email'], $hash, $subject, $body)) {
        addLog("Сообщение ошибки: \n" . mysqli_error($mysqli));
    }
}

function getUsersForSending($mysqli, $daysBeforeNotification = 3)
{
    $sql = "select * from `users` 
            where (`confirmed` = 1 or (`checked` = 1 and `valid` = 1)) 
              and `subscribed` = 1 
              and `valid_till` = curdate() + interval {$daysBeforeNotification} day
              and `valid_till` > ifnull(`notification_sent`, '1970-01-01')
            order by `valid_till` asc";
    return mysqli_query($mysqli, $sql);
}

function addTaskToQueue($mysqli, string $emailFrom, string $emailTo, string $hash, string $subject, string $body)
{
    $sql = "insert into `queue_emails` (`from`, `to`, `hash`, `subject`, `body`) 
            values ('{$emailFrom}', '{$emailTo}', '{$hash}', '{$subject}', '{$body}')";
    return mysqli_query($mysqli, $sql);
}

function getMessageSubject(): string
{
    return 'your subscription is expiring soon';
}

function getMessageBody(string $username, int $userId, string $hash): string
{
    $hash = urlencode($hash);
    $domain = APP_DOMAIN;
    return "<html><head></head><body>
                {$username}, your subscription is expiring soon.
                <br />
                <a href='{$domain}/unsubscribe.php?hash={$hash}'>Unsubscribe</a> 
            </body></html>";
}

function getHashByUserId(int $userId): string
{
    return encrypt($userId);
}


mysqli_close($mysqli);