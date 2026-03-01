<?php
session_start();

// 1. Alles inladen via Composer (vervangt env_loader.php)
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Namespaces importeren
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$session_id = $_GET['session_id'] ?? null;
$time = time();

// Veiligheidscheck
if (!$session_id || !isset($_SESSION['klant_email'])) {
    header('Location: index.php');
    exit();
}

$targetDir = 'data/certifications';

// Controleer of de map al bestaat
if (!is_dir($targetDir)) {
    // Maak de map aan met de juiste rechten (0755 is standaard voor mappen)
    // 'true' zorgt ervoor dat ook bovenliggende mappen worden aangemaakt indien nodig
    if (mkdir($targetDir, 0755, true)) {
        echo "De map '$targetDir' is succesvol aangemaakt.";
    } else {
        echo "Fout: Kon de map niet aanmaken. Check je schrijfrechten.";
    }
}

// 2. JOUW VERTROUWDE STRIPE CHECK (via cURL)
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/checkout/sessions/$session_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':');
$result = json_decode(curl_exec($ch), true);
curl_close($ch);

// Alleen doorgaan als de status echt 'paid' is
if (!isset($result['payment_status']) || $result['payment_status'] !== 'paid') {
    header('Location: index.php?error=payment_failed');
    exit();
}

// Variabelen klaarzetten voor gebruik
$klant_naam = $_SESSION['klant_naam'];
$klant_email = $_SESSION['klant_email'];
$pakket_titel = $_SESSION['gekozen_pakket']['title'];
$pakket_prijs = $_SESSION['gekozen_pakket']['price'];
$cert_id = "OP-" . strtoupper(substr(md5($session_id), 0, 8));
$datum = date('d-m-Y');

// 3. DATABASE UPDATES (Sales & Bezitters)
$salesFile = 'data/sales.json';
if (file_exists($salesFile)) {
    $salesData = json_decode(file_get_contents($salesFile), true);
    $salesData['total_revenue'] += $pakket_prijs;
    $salesData['certificates_sold'] = ($salesData['certificates_sold'] ?? 0) + 1;
    file_put_contents($salesFile, json_encode($salesData, JSON_PRETTY_PRINT));
}

// Toevoegen aan bezitters.json om dubbele claims te voorkomen
$bezittersFile = 'data/bezitters.json';
$bezitters = json_decode(file_get_contents($bezittersFile), true) ?: [];
if (!in_array($klant_email, $bezitters)) {
    $bezitters[] = $klant_email;
    file_put_contents($bezittersFile, json_encode($bezitters, JSON_PRETTY_PRINT));
}

// 4. PDF GENEREREN (Luxe Editie)
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

// Achtergrondkleur (heel licht grijs/blauw voor diepte)
$pdf->SetFillColor(248, 250, 252);
$pdf->Rect(0, 0, 297, 210, 'F');

// Luxe Randen
$pdf->SetDrawColor(0, 168, 255); // OxyPure Blue
$pdf->SetLineWidth(1.5);
$pdf->Rect(10, 10, 277, 190); // Buitenste dikke rand
$pdf->SetLineWidth(0.2);
$pdf->Rect(13, 13, 271, 184); // Binnenste dunne sierlijn

// Logo of Merknaam bovenaan
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(0, 168, 255);
$pdf->Cell(0, 20, 'OxyPure', 0, 1, 'C');
$pdf->SetDrawColor(226, 232, 240);
$pdf->Line(110, 32, 187, 32); // Sierlijntje onder logo

// Hoofdtitel
$pdf->SetTextColor(26, 42, 58); // Navy
$pdf->SetFont('Arial', 'B', 38);
$pdf->Ln(20);
$pdf->Cell(0, 15, 'CERTIFICAAT VAN PUURHEID', 0, 1, 'C');

// Decoratieve tekst
$pdf->SetFont('Arial', '', 14);
$pdf->SetTextColor(100, 116, 139);
$pdf->Ln(15);
$pdf->Cell(0, 10, 'Hiermee wordt officieel en onherroepelijk vastgelegd dat', 0, 1, 'C');

// De Naam van de Eigenaar (Groot en opvallend)
$pdf->SetFont('Times', 'BI', 42);
$pdf->SetTextColor(0, 168, 255);
$pdf->Ln(10);

// Converteer UTF-8 naar ISO-8859-1 voor correcte weergave van accenten
$geconverteerde_naam = iconv('UTF-8', 'windows-1252', $klant_naam);
$pdf->Cell(0, 25, $geconverteerde_naam, 0, 1, 'C');

// Beschrijving
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(100, 116, 139);
$pdf->Ln(5);
$pdf->MultiCell(0, 8, "Voldoet aan de atmosferische standaarden van OxyPure.\nEigenaar van een uniek segment gecertificeerde zuivere lucht.", 0, 'C');

// Validatie Stempel (Visueel element)
//$pdf->SetXY(210, 140);
//$pdf->SetFont('Arial', 'B', 10);
//$pdf->SetTextColor(0, 168, 255);
//$pdf->Cell(40, 40, 'OFFICIEEL GEVALIDEERD', 1, 0, 'C'); // Simpele box als 'stempel'

// Footer met ID en Datum
$pdf->SetY(-30); // Plaats de cursor op 25mm van de onderkant
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(148, 163, 184);
$pdf->Cell(0, 10, "Certificaat ID: $cert_id  |  Datum van Uitgifte: $datum", 0, 0, 'C');

// Bestand opslaan
$pdf_path = $targetDir . '/certificaat_' . str_replace(' ', '_', $klant_naam) . '_' . $time . '-air.pdf';
$pdf->Output('F', $pdf_path);

// 5. BEVESTIGINGSMAIL (Via PHPMailer/SMTP)
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $_ENV['SMTP_PORT'] ?? 587;

    $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], 'OxyPure');
    $mail->addAddress($klant_email, $klant_naam);
    $mail->addAttachment($pdf_path, 'Uw_OxyPure_Certificaat.pdf');

    $mail->isHTML(true);
    $mail->Subject = "Uw Certificaat van Puurheid - OxyPure";
    $mail->Body    = "<h2>Gefeliciteerd, $klant_naam!</h2>
                      <p>Uw aankoop van <strong>$pakket_titel</strong> is succesvol verwerkt.</p>
                      <p>U bent nu officieel onderdeel van de elite. Uw certificaat is bijgevoegd als PDF.</p>
                      <br><p>Met zuivere groet,<br>Het OxyPure Team</p>";
    $mail->send();
} catch (Exception $e) {
    // Log fout indien nodig
}

// Sessie opschonen
unset($_SESSION['klant_naam']);
unset($_SESSION['klant_email']);

include('includes/header.php');
?>

<section class="section text-center" style="padding-top: 150px;">
    <div class="container" style="max-width: 600px;">
        <div class="success-card" style="background: white; padding: 50px; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05);">
            <h1 class="gradient-text">Betaling Geslaagd!</h1>
            <p>Welkom bij de elite, <strong><?php echo htmlspecialchars($klant_naam); ?></strong>.</p>

            <div class="cert-box" style="border: 2px dashed #00a8ff; padding: 30px; margin: 30px 0; border-radius: 20px;">
                <h3>CERTIFICAAT VAN PUURHEID</h3>
                <p>Status: <span style="color: #00a8ff; font-weight: bold;">OFFICIEEL GEREGISTREERD</span></p>
                <br>
                <a href="<?php echo $pdf_path; ?>" download class="btn-main" style="display: inline-block; text-decoration: none;">
                    Download Uw Certificaat (.air)
                </a>
            </div>

            <a href="index.php" style="color: #64748b; text-decoration: none;">Terug naar de homepagina</a>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>