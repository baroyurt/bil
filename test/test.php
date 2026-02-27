<?php
$ad      = '';
$uye_no  = '';
$tarih   = '';
$seviye  = 'Diamond';
$etkinlik = '28 Subat Prestige';
$adet    = 1;
$biletler = [];
$hata    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad       = strtoupper(trim($_POST['ad']       ?? ''));
    $uye_no   = trim($_POST['uye_no']  ?? '');
    $tarih    = trim($_POST['tarih']   ?? '');
    $seviye   = trim($_POST['seviye']  ?? 'Diamond');
    $etkinlik = trim($_POST['etkinlik'] ?? '28 Subat Prestige');
    $adet     = intval($_POST['adet']  ?? 1);

    if ($adet < 1)   { $adet = 1; }
    if ($adet > 100) { $hata = 'Bilet sayısı 100\'ü geçemez!'; $adet = 100; }

    $kullanilan = [];
    $zaman_damgasi = date('d.m.y H:i');

    for ($i = 1; $i <= $adet; $i++) {
        do {
            $no = rand(1000, 9999);
        } while (in_array($no, $kullanilan));
        $kullanilan[] = $no;

        $biletler[] = [
            'no'     => $no,
            'sira'   => $i,
            'toplam' => $adet,
            'zaman'  => $zaman_damgasi,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bilet Yazdırma</title>
<style>
/* ---- SCREEN ---- */
@media screen {
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #2c2c2c;
        margin: 0;
        padding: 20px;
    }
    .form-wrap {
        background: #fff;
        border-radius: 10px;
        max-width: 480px;
        margin: 0 auto 30px;
        padding: 28px 32px;
        box-shadow: 0 4px 20px rgba(0,0,0,.4);
    }
    .form-wrap h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.3em;
        color: #1a1a1a;
        letter-spacing: 1px;
    }
    .form-group {
        margin-bottom: 14px;
    }
    .form-group label {
        display: block;
        font-size: .85em;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: .95em;
        box-sizing: border-box;
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }
    .btn {
        flex: 1;
        padding: 11px;
        border: none;
        border-radius: 7px;
        font-size: 1em;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s;
    }
    .btn:hover { opacity: .85; }
    .btn-primary { background: #1a1a1a; color: #fff; }
    .btn-print   { background: #c8a84b; color: #1a1a1a; }
    .hata {
        background: #ffe0e0;
        color: #c00;
        border-radius: 6px;
        padding: 9px 14px;
        margin-bottom: 14px;
        font-weight: 600;
        font-size: .9em;
    }
    .biletler-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .bilet-screen-frame {
        border: 1px solid #555;
        border-radius: 4px;
        background: #fff;
        padding: 0;
        box-shadow: 0 2px 12px rgba(0,0,0,.5);
    }
}

/* ---- PRINT ---- */
@media print {
    body { margin: 0; padding: 0; background: #fff; }
    .form-wrap,
    .no-print { display: none !important; }

    @page {
        size: 80mm auto;
        margin: 0;
    }

    .biletler-wrap {
        display: block;
    }

    .bilet-screen-frame {
        border: none;
        box-shadow: none;
        page-break-after: always;
    }
}

/* ---- TICKET ---- */
.bilet {
    width: 72mm;
    min-height: 112mm;
    box-sizing: border-box;
    font-family: 'Courier New', Courier, monospace;
    text-align: center;
    padding: 4mm 3mm 3mm;
    background: #fff;
    color: #000;
}

.bilet .bilet-logo {
    width: 66mm;
    height: auto;
    display: block;
    margin: 0 auto 2mm;
    filter: grayscale(100%);
}

.bilet .etkinlik-bar {
    display: block;
    background: #000;
    color: #fff;
    font-size: 13pt;
    font-weight: 700;
    font-style: italic;
    padding: 2mm 3mm;
    margin: 2mm 0;
    letter-spacing: 1px;
}

.bilet .misafir-ad {
    display: block;
    font-size: 11pt;
    font-weight: 700;
    margin: 2mm 0 1mm;
    letter-spacing: 1px;
}

.bilet .uye-bilgi {
    display: block;
    font-size: 9pt;
    margin-bottom: 2mm;
}

.bilet .dashed {
    border: none;
    border-top: 1px dashed #000;
    margin: 2mm 0;
}

.bilet .ticket-no {
    display: block;
    font-size: 20pt;
    font-weight: 900;
    margin: 2mm 0;
    letter-spacing: 1px;
}

.bilet .zaman-sira {
    display: block;
    font-size: 9pt;
    margin: 2mm 0 1mm;
}
</style>
</head>
<body>

<!-- FORM -->
<div class="form-wrap no-print">
    <h2>🎫 BİLET YAZDIRMA SİSTEMİ</h2>

    <?php if ($hata): ?>
        <div class="hata">⚠️ <?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Etkinlik Adı</label>
            <input type="text" name="etkinlik"
                   value="<?= htmlspecialchars($_POST['etkinlik'] ?? '28 Subat Prestige') ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Misafir Adı SOYADI</label>
            <input type="text" name="ad"
                   value="<?= htmlspecialchars($_POST['ad'] ?? '') ?>"
                   placeholder="ULAS GENCAN"
                   required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Üye No</label>
                <input type="text" name="uye_no"
                       value="<?= htmlspecialchars($_POST['uye_no'] ?? '') ?>"
                       placeholder="50007"
                       required>
            </div>
            <div class="form-group">
                <label>Tarih Kodu</label>
                <input type="text" name="tarih"
                       value="<?= htmlspecialchars($_POST['tarih'] ?? '') ?>"
                       placeholder="20260227"
                       required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Seviye</label>
                <select name="seviye">
                    <?php
                    $seviyeler = ['Diamond','Platinum','Gold','Silver','Bronze'];
                    $sec = $_POST['seviye'] ?? 'Diamond';
                    foreach ($seviyeler as $s):
                    ?>
                    <option value="<?= $s ?>" <?= $sec === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Bilet Adedi <small>(maks. 100)</small></label>
                <input type="number" name="adet" min="1" max="100"
                       value="<?= intval($_POST['adet'] ?? 1) ?>"
                       required>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Bilet Oluştur</button>
            <?php if (!empty($biletler)): ?>
            <button type="button" class="btn btn-print" onclick="window.print()">🖨️ Yazdır</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- TICKETS -->
<?php if (!empty($biletler)): ?>
<div class="biletler-wrap">
    <?php foreach ($biletler as $b): ?>
    <div class="bilet-screen-frame">
        <div class="bilet">

            <!-- Logo / Header -->
            <img src="logogrand.png" class="bilet-logo" alt="Chamada Logo">

            <!-- Event bar -->
            <span class="etkinlik-bar"><?= htmlspecialchars($etkinlik) ?></span>

            <!-- Guest info -->
            <span class="misafir-ad"><?= htmlspecialchars($ad) ?></span>
            <span class="uye-bilgi">
                <?= htmlspecialchars($uye_no) ?> &ndash; <?= htmlspecialchars($tarih) ?> &ndash; <?= htmlspecialchars($seviye) ?>
            </span>

            <hr class="dashed">

            <span class="ticket-no">Ticket:<?= $b['no'] ?></span>

            <hr class="dashed">

            <span class="zaman-sira">
                <?= $b['zaman'] ?> &ndash; (<?= $b['sira'] ?>/<?= $b['toplam'] ?>)
            </span>

        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</body>
</html>