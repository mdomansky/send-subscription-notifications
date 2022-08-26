create table users
(
    `id`                int auto_increment,
    `username`          varchar(256) null,
    `email`             varchar(256) not null,
    `valid_till`        date         null,
    `confirmed`         boolean      null,
    `checked`           boolean      null,
    `valid`             boolean      null,
    `subscribed`        boolean      null,
    `notification_sent` date         null,
    constraint users_pk
        primary key (id)
);

create unique index users_email_uindex
    on users (email);


create table queue_emails
(
    `id`      int auto_increment,
    `from`    varchar(256) null,
    `to`      varchar(256) not null,
    `hash`    varchar(256) not null,
    `subject` varchar(256) not null,
    `body`    text         not null,
    constraint queue_emails_pk
        primary key (id)
);

