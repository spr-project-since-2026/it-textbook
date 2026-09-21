<?php

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_token(): string
{
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(string $token): bool
{
    return hash_equals(
        $_SESSION['csrf_token'] ?? '',
        $token
    );
}
