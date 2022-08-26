# Task
Create a service that sends notifications before user's subscription is expired. We have a list of users with emails. Some of them confirmed their emails and we know that their emails are valid. The rest emails were not confirmed so we need to check the email validity before sending. We should not send emails that would not be delivered.
* There are 1.000.000 users
* Sending method takes from 1 to 10 seconds.
* Checking emails validity takes from 1 to 60 seconds and costs money. 
* Do not use frameworks and OOP


# Solution
Note: 1.000.000  users / 365 = 2739 email per day in average. Even in case x10 (almost 30k emails per day), it is still normal.

My approach is to separate the task for several steps:

### prepare-emails.php
On daily basis the script gets users whose subscriptions expiring within 30 days and checks their emails for validity. Having checked it updates field `checked=1` to avoid checking it again. The field `valid` shows if the email is valid and the message would be delivered.

### send-emails-to-queue.php
On daily basis the script gets users whose subscriptions expiring in 3 days, prepare all data for the sending and puts the task to the `queue_emails` table. It uses `hash` property (it is encrypted with a key and can be decrypted) to give the link for unsubscription.

### send-emails-from-queue.php
The service sends messages from the `queue_emails` table in an infinity loop with sleep 1 second. 1 day has 86400 seconds that is greater than task requires. 

In order to get in INBOX folder of users' emails, script should add `List-Unsubscribe` Header to the message with link for unsubscription. Also, developer need to set up DXF settings of the server, set resolver properly and check emails https://www.mail-tester.com/.

The script can be used as a service from different servers in order to send huge amount of emails in short period of time and not be banned.

### unsubscribe.php
Each email has a link for unsubscription like http://domain.com/unsubscribe.php?hash=23oisdnfo3inwk3ung. The link has hash to hide `user_id` or `email`, but keep the functionality.


# Problems
* Can be sent several emails to one recipient by calling send-emails-to-queue.php several times. As a solution we could add extra field `last_sent_email` to store the date of last sent email
* We do not know the results if emails are delivered and where they are got to. As a solution we could add transparent pixel to the email
* We do not know the results of sending in case receiver mail server rejects emails (for example, due to spam)