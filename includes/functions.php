<?php

function checkEmail(string $email): bool {
    //TODO: add regexp validator
    sleep(rand(0, 60));
    return rand(0, 1);
}

function sendEmail($from, $to, $hash, $subj, $body) {
    sleep(rand(0, 10));
}

function encrypt($data): string {
    return openssl_encrypt($data, CRYPTO_METHOD, CRYPTO_KEY, 0, CRYPTO_IV);
}

function decrypt($data): string {
    return openssl_decrypt($data, CRYPTO_METHOD, CRYPTO_KEY, 0, CRYPTO_IV);
}