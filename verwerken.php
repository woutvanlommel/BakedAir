<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['gekozen_pakket'])) {
    $pakket = $_SESSION['gekozen_pakket'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $naam = htmlspecialchars($_POST['naam']);

    // --- NIEUW: Check op dubbele bestelling ---
    $bezitters = json_decode(file_get_contents('data/bezitters.json'), true) ?: [];
    if (in_array($email, $bezitters)) {
        header('Location: bestellen.php?error=already_claimed');
        exit();
    }

    $_SESSION['klant_naam'] = $naam;
    $_SESSION['klant_email'] = $email;

    $stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
    $base_url = $_ENV['BASE_URL'];

    // Maak de Stripe Checkout Sessie aan
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/checkout/sessions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $params = http_build_query([
        'mode' => 'payment',
        'customer_email' => $_SESSION['klant_email'],
        'success_url' => $base_url . 'succes.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $base_url . 'bestellen.php?pakket=' . $pakket['id'],
        'line_items[0][price_data][currency]' => 'eur',
        'line_items[0][price_data][product_data][name]' => 'OxyPure: ' . $pakket['title'],
        'line_items[0][price_data][unit_amount]' => ($pakket['price'] * 100), // Stripe werkt in centen
        'line_items[0][quantity]' => 1,
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':');

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (isset($result['url'])) {
        header("Location: " . $result['url']); // Ga naar de Stripe betaalpagina
        exit();
    } else {
        die("Stripe Fout: " . ($result['error']['message'] ?? 'Onbekende fout'));
    }
}
