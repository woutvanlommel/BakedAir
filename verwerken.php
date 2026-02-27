<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['gekozen_pakket'])) {
    // Sla klantgegevens op in de sessie voor later gebruik op success.php
    $_SESSION['klant_naam'] = htmlspecialchars($_POST['naam']);
    $_SESSION['klant_email'] = htmlspecialchars($_POST['email']);

    // Optioneel: Sla de lead op in een JSON-bestand voor je administratie
    $lead = [
        'datum' => date('Y-m-d H:i:s'),
        'naam' => $_SESSION['klant_naam'],
        'email' => $_SESSION['klant_email'],
        'pakket' => $_SESSION['gekozen_pakket']['title'],
        'prijs' => $_SESSION['gekozen_pakket']['price']
    ];

    $leadsFile = 'data/leads.json';
    $huidigeLeads = file_exists($leadsFile) ? json_decode(file_get_contents($leadsFile), true) : [];
    $huidigeLeads[] = $lead;
    file_put_contents($leadsFile, json_encode($huidigeLeads, JSON_PRETTY_PRINT));

    // Stuur de klant door naar de Ko-fi betaalpagina
    header('Location: ' . $_SESSION['gekozen_pakket']['kofi_url']);
    exit();
} else {
    header('Location: index.php');
    exit();
}
