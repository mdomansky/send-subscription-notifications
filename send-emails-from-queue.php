<?php
/*
 * The service sends messages from the `queue_emails` table in an infinity loop with sleep 1 second.
 * 1 day has 86400 seconds that is greater than task requires. In order to get in INBOX folder of users' emails, script
 * should add `List-Unsubscribe` Header to the message with link for unsubscription. Also, developer need to set up
 * DXF settings of the server, set resolver properly and check emails https://www.mail-tester.com/.
 *
 * The script can be used as a service from different servers in order to send huge amount of emails in short period
 * of time and not be banned.
 */

set_time_limit(0);
$sleep = 1;

require '_config.php';

while (true) {
    $sql = "select * from `queue_emails` order by `id` asc limit 1";
    $result = mysqli_query($mysqli, $sql);
    $task = mysqli_fetch_assoc($result);

    if ($task) {
        var_dump($task);
        sendEmail($task['from'], $task['to'], $task['hash'], $task['subject'], $task['body']);

        $sql = "delete from `queue_emails` where `id` = {$task['id']}";
        $result = mysqli_query($mysqli, $sql);
        if (!mysqli_query($mysqli, $sql)) {
            addLog("Сообщение ошибки: %s\n" . mysqli_error($mysqli));
        }
    }

    sleep($sleep);
}

mysqli_close($mysqli);