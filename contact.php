<?php
include 'includes/header.php';

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam    = htmlspecialchars(trim($_POST['naam'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $onderwerp = htmlspecialchars(trim($_POST['onderwerp'] ?? ''));
    $bericht = htmlspecialchars(trim($_POST['bericht'] ?? ''));

    if (!$naam)    $errors[] = 'Naam is verplicht.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Vul een geldig e-mailadres in.';
    if (!$bericht) $errors[] = 'Bericht is verplicht.';

    if (empty($errors)) {
        // Opslaan als lead
        $leadsFile = 'data/leads.json';
        $leads = json_decode(file_get_contents($leadsFile), true) ?: [];
        $leads[] = [
            'naam'       => $naam,
            'email'      => $email,
            'onderwerp'  => $onderwerp,
            'bericht'    => $bericht,
            'datum'      => date('d-m-Y H:i'),
        ];
        file_put_contents($leadsFile, json_encode($leads, JSON_PRETTY_PRINT));
        $success = true;
    }
}
?>

<main>
    <section class="contact-hero">
        <div class="container text-center">
            <span class="badge">Neem Contact Op</span>
            <h1>Heeft u vragen over<br><span class="gradient-text">uw luchtaandeel?</span></h1>
            <p class="hero-sub">Ons team van atmosferische experts staat klaar om u te begeleiden.</p>
        </div>
    </section>

    <section class="section">
        <div class="container contact-grid">

            <!-- Linker kolom: Formulier -->
            <div class="contact-form-col">
                <?php if ($success): ?>
                    <div class="contact-success">
                        <span class="contact-success-icon">✓</span>
                        <h3>Bericht verzonden!</h3>
                        <p>We nemen zo spoedig mogelijk contact met u op.</p>
                    </div>
                <?php else: ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert-error">
                            <?php foreach ($errors as $e): ?>
                                <p>⚠️ <?php echo $e; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="contact.php" class="contact-form">
                        <div class="form-group">
                            <label for="naam">Volledige naam</label>
                            <input type="text" id="naam" name="naam" required placeholder="Uw naam"
                                value="<?php echo htmlspecialchars($_POST['naam'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mailadres</label>
                            <input type="email" id="email" name="email" required placeholder="bijv. naam@domein.be"
                                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="onderwerp">Onderwerp</label>
                            <input type="text" id="onderwerp" name="onderwerp" placeholder="Waarover gaat uw vraag?"
                                value="<?php echo htmlspecialchars($_POST['onderwerp'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="bericht">Bericht</label>
                            <textarea id="bericht" name="bericht" required rows="6"
                                placeholder="Schrijf hier uw bericht..."><?php echo htmlspecialchars($_POST['bericht'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn-submit-order">Verstuur Bericht</button>
                    </form>
                <?php endif; ?>
            </div>

            <!-- Rechter kolom: Contactinfo -->
            <aside class="contact-info-col">
                <div class="contact-info-card">
                    <h3>Contactgegevens</h3>
                    <ul class="contact-info-list">
                        <li>
                            <span class="contact-info-icon">✉</span>
                            <div>
                                <strong>E-mail</strong>
                                <span>info@oxypure.be</span>
                            </div>
                        </li>
                        <li>
                            <span class="contact-info-icon">☎</span>
                            <div>
                                <strong>Telefoon</strong>
                                <span>+32 11 000 000</span>
                            </div>
                        </li>
                        <li>
                            <span class="contact-info-icon">◷</span>
                            <div>
                                <strong>Kijk in het luchtruim voor zuivere openingsuren</strong>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="contact-note">
                    <p>OxyPure is een satirisch project. Wij garanderen geen echte atmosferische assets.</p>
                </div>
            </aside>

        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>