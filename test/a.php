<?php
/**
 * --- KONFİGÜRASYON ---
 */
$logoCasino  = 'logogrand.png'; // Logo dosya adınız
$raffleName  = '28 Subat Prestige'; // Siyah bar içindeki yazı

// Layout Ayarı: Buradaki sıralamayı değiştirerek bilet yapısını yönetebilirsiniz
$layoutTicket = "logoCasino,rafflename,playerName,playerInfo,ticketNo,footer";
$layoutElements = explode(',', $layoutTicket);

$isSubmitted = false;
$ticketsHtml = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isSubmitted = true;
    
    $pName = strtoupper($_POST['playerName'] ?? '');
    $pInfo = strtoupper($_POST['playerInfo'] ?? '');
    $adet  = (int)($_POST['adet'] ?? 1);

    for ($i = 0; $i < $adet; $i++) {
        // Random bilet numarası üretimi
        $randomNum = rand(1000, 9999);
        $ticketNoText = "Ticket:" . $randomNum;
        
        // Alt bilgi: Tarih ve Sayaç (Örn: 27.02.26 18:15 - (1/3))
        $dateStr = date('d.m.y H:i');
        $current = $i + 1;
        $footerText = "{$dateStr} - ({$current}/{$adet})";

        // Her bilet için HTML bloğu oluştur
        $ticketsHtml .= "<div class='ticket-container'>\n";
        
        foreach ($layoutElements as $item) {
            $item = trim($item);
            switch ($item) {
                case 'logoCasino':
                    $ticketsHtml .= "<img class='logo' src='{$logoCasino}' alt='Logo'>\n";
                    break;
                case 'rafflename':
                    $ticketsHtml .= "<div class='black-bar'>{$raffleName}</div>\n";
                    break;
                case 'playerName':
                    $ticketsHtml .= "<div class='player-name'>{$pName}</div>\n";
                    break;
                case 'playerInfo':
                    $ticketsHtml .= "<div class='player-info'>{$pInfo}</div>\n";
                    break;
                case 'ticketNo':
                    $ticketsHtml .= "<div class='dashed'>----------------------------------</div>\n";
                    $ticketsHtml .= "<div class='ticket-no'>{$ticketNoText}</div>\n";
                    $ticketsHtml .= "<div class='dashed'>----------------------------------</div>\n";
                    break;
                case 'footer':
                    $ticketsHtml .= "<div class='footer-text'>{$footerText}</div>\n";
                    break;
            }
        }
        $ticketsHtml .= "</div>\n";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Chamada Prestige - Bilet Sistemi</title>
    <style>
        /* Arayüz Stilleri */
        body { 
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 40px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        
        .admin-panel { 
            background: #fff; 
            padding: 25px; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 400px; 
            margin-bottom: 50px; 
        }
        
        .admin-panel h2 { margin-top: 0; color: #333; font-size: 20px; text-align: center; }
        
        label { display: block; margin-top: 15px; font-weight: 600; color: #555; font-size: 14px; }
        
        input { 
            width: 100%; 
            padding: 12px; 
            margin-top: 5px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-size: 15px; 
        }

        .btn-print { 
            width: 100%; 
            padding: 15px; 
            background: #1a1a1a; 
            color: #fff; 
            border: none; 
            border-radius: 6px; 
            margin-top: 20px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: background 0.3s;
        }
        
        .btn-print:hover { background: #000; }

        /* Termal Bilet Tasarımı */
        .ticket-container { 
            background: #fff; 
            width: 320px; 
            padding: 20px; 
            text-align: center; 
            color: #000; 
            border: 1px solid #eee;
            margin-bottom: 30px;
        }

        .logo { width: 90%; height: auto; margin-bottom: 10px; filter: grayscale(100%); }

        .black-bar { 
            background: #000; 
            color: #fff; 
            font-family: Arial, sans-serif;
            font-size: 20px; 
            font-weight: bold; 
            padding: 8px 0; 
            margin: 10px 0; 
            width: 100%;
        }

        .player-name { font-size: 18px; font-weight: bold; margin-top: 5px; }
        
        .player-info { font-size: 15px; margin-bottom: 10px; }

        .dashed { font-size: 12px; letter-spacing: -1px; margin: 2px 0; }

        .ticket-no { font-size: 28px; font-weight: bold; padding: 5px 0; }

        .footer-text { font-size: 14px; margin-top: 8px; font-weight: normal; }

        /* Yazdırma Ayarları */
        @media print {
            .admin-panel { display: none; }
            body { background: #fff; padding: 0; margin: 0; }
            .ticket-container { 
                box-shadow: none; 
                border: none; 
                margin: 0; 
                width: 100%; 
                page-break-after: always; 
            }
        }
    </style>
</head>
<body>

    <div class="admin-panel">
        <h2>Bilet Basım Sistemi</h2>
        <form method="POST">
            <label>Oyuncu Adı Soyadı:</label>
            <input type="text" name="playerName" placeholder="Örn: ULAS GENCAN" required>
            
            <label>Oyuncu Bilgileri (ID/Tarih/Kart):</label>
            <input type="text" name="playerInfo" placeholder="Örn: 50007 - 20260227 - Diamond" required>
            
            <label>Basılacak Adet:</label>
            <input type="number" name="adet" min="1" max="100" value="1" required>
            
            <button type="submit" class="btn-print">BİLETLERİ OLUŞTUR VE YAZDIR</button>
        </form>
    </div>

    <?php if ($isSubmitted): ?>
        <div id="print-area">
            <?= $ticketsHtml ?>
        </div>
        
        <script>
            // Sayfa hazır olduğunda otomatik yazdırma diyaloğunu aç
            window.onload = function() {
                window.print();
            };
        </script>
    <?php endif; ?>

</body>
</html>