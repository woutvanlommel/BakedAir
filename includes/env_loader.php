<?php
// includes/env_loader.php

function loadEnv($path)
{
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Negeer commentaarregels
        if (strpos(trim($line), '#') === 0) continue;

        // Splits op de eerste '='
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Roep de functie direct aan voor de root-map
loadEnv(__DIR__ . '/../.env');
