<?php

// DB
const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_DBNAME = 'send_norifications';

// emails
const EMAIL_FROM = 'admin@localhost.loc';
const CRYPTO_METHOD = "AES-192-CBC";
const CRYPTO_KEY = '5aa3c281e42ba7101f7227a7519d5e961c7bcf2b10a42914304bffc1afcebb1d2be98f53caa80d05';
const CRYPTO_IV = 'sdfdsfdsfdsfdsfd';

// service
const APP_DOMAIN = 'http://localhost';

require 'includes/logs.php';
require 'includes/databases.php';
require 'includes/functions.php';
