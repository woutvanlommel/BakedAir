<?php
$salesData = json_decode(file_get_contents('data/sales.json'), true);
?>

<section id="waarom" class="section asset-grading">
    <div class="container grid-main">
        <div class="info-content">
            <span class="label">SYSTEEM STATUS: KRITIEK</span>
            <h2>Lucht is te kostbaar om <span class="gradient-text">gratis</span> te laten.</h2>
            <p class="lead">Waarom zou u vertrouwen op de publieke atmosfeer als u een privaat, gecertificeerd segment kunt bezitten? Gratis lucht is een marktinefficiëntie die we nu corrigeren.</p>
            <p>OxyPure digitaliseert schaarste. Door uw ademhaling te koppelen aan een uniek .air bestand, verzekert u uw plek in de post-fysieke economie.</p>

            <ul class="feature-list-tech">
                <li><span class="tech-dot"></span> <span><strong>Gevalideerd Eigendom</strong> - Stop met ademen van andermans restproduct; claim uw eigen ID.</span></li>
                <li><span class="tech-dot"></span> <span><strong>Inflatiebestendig</strong> - In tegenstelling tot de euro, maken wij geen nieuwe lucht bij.</span></li>
                <li><span class="tech-dot"></span> <span><strong>Digitale Sovereigniteit</strong> - Uw longinhoud, uw blockchain-verificatie, uw winst.</span></li>
            </ul>
        </div>

        <div class="info-visual">
            <div class="data-box">
                <div class="header-status">
                    <span class="pulse"></span> LIVE MARKET DATA
                </div>
                <div class="data-row">
                    <span>OXY/INDEX</span>
                    <span class="up">+24.8%</span>
                </div>
                <div class="data-row">
                    <span>Supply Cap</span>
                    <span>10.000 ATMOS</span>
                </div>
                <div class="data-row">
                    <span>In Omloop</span>
                    <span><?php echo $salesData['certificates_sold']; ?></span>
                </div>
                <div class="data-row highlight">
                    <span>Marktsentiment</span>
                    <span>HYPER-BULLISH</span>
                </div>
            </div>
            <p class="disclaimer">* Resultaten uit het verleden bieden geen garantie voor toekomstige ademhaling.</p>
        </div>
    </div>
</section>