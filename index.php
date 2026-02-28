<?php include('includes/header.php');

// data_init.php
$files = [
    'data/sales.json' => ['total_revenue' => 0, 'target_goal' => 1000000, 'certificates_sold' => 0, 'total_available' => 10000],
    'data/leads.json' => [],
    'data/bezitters.json' => []
];

foreach ($files as $path => $default) {
    if (!file_exists($path)) {
        file_put_contents($path, json_encode($default, JSON_PRETTY_PRINT));
    }
}
?>

<main>

    <?php
    include "sections/hero.php";
    include "sections/waarom.php";
    include "sections/collectie.php";
    include "sections/ervaringen.php";
    ?>

</main>

<?php include('includes/footer.php'); ?>