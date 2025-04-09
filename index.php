<?php
// Ensure no output before session_start()
session_start();

// Doğru giriş bilgileri
$dogru_kullanici = "seyda21";
$dogru_sifre = "123456";
// Giriş hakkı sınırı
if (!isset($_SESSION['giris_hakki'])) {
    $_SESSION['giris_hakki'] = 3;
}

// Kullanıcı giriş yapmaya çalıştıysa
$mesaj = "";
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $kullanici = $_POST["username"];
        $sifre = $_POST["password"];

        if ($_SESSION['giris_hakki'] > 0) {
            if ($kullanici !== $dogru_kullanici) {
                $mesaj = "Kullanıcı adı hatalıdır!";
                $_SESSION['giris_hakki']--;
            } elseif ($sifre !== $dogru_sifre) {
                $mesaj = "Şifre hatalıdır!";
                $_SESSION['giris_hakki']--;
            } else {
                $mesaj = "Başarıyla giriş yaptınız!";
                $_SESSION['giris_hakki'] = 3; // Başarılı giriş sonrası sıfırla
            }
        } else {
            $mesaj = "Giriş hakkınız doldu!";
        }
    }

    // Handle "Çıkış" button
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Ekranı</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            text-align: left;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 2px solid #ccc; /* Daha belirgin bir border */
            border-radius: 12px; /* Daha yuvarlak köşeler */
            box-sizing: border-box;
            transition: all 0.3s ease; /* Daha yumuşak geçişler */
            background-color: #f9f9f9; /* Hafif bir arka plan rengi */
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007bff; /* Odaklanıldığında mavi border */
            background-color: #eef6ff; /* Hafif mavi arka plan */
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5); /* Hafif bir mavi gölge */
        }
        .giris {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 12px; /* Daha yuvarlak köşeler */
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Hover ve tıklama animasyonu */
        }
        .giris:hover {
            background-color: #0056b3;
            transform: scale(1.05); /* Hover sırasında hafif büyüme efekti */
        }
        .cikis {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 12px; /* Daha yuvarlak köşeler */
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease, transform 0.2s ease; /* Hover ve tıklama animasyonu */
        }
        .cikis:hover {
            background-color: #a71d2a;
            transform: scale(1.05); /* Hover sırasında hafif büyüme efekti */
        }
        .datetime {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
            font-weight: bold;
        }
        .error {
            color: #dc3545;
            margin-top: 10px;
            font-size: 14px;
        }
        .success {
            color: #28a745;
            margin-top: 10px;
            font-size: 14px;
        }
        .limit {
            margin-top: 10px;
            font-weight: bold;
            color: #ff5722;
            font-size: 14px;
        }
        h3 {
            margin-bottom: 10px;
            color: #007bff;
            font-size: 18px;
        }
        div[style="border: 1px solid black;"] {
            border: 2px solid #ddd; /* Daha yumuşak bir border */
            border-radius: 12px; /* Yuvarlak köşeler */
            padding: 15px; /* İçerik için boşluk */
            background-color: #f9f9f9; /* Hafif bir arka plan rengi */
        }
        .datetime {
            border: 2px solid #ddd; /* Daha yumuşak bir border */
            border-radius: 12px; /* Yuvarlak köşeler */
            padding: 15px; /* İçerik için boşluk */
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        <h3 style="margin-right: 180px;">Tarih - Saat</h3>
        <div class="datetime" id="datetime" style="border:1px solid black; color: blue;"></div>
        <form method="POST" action="">
            <h3 style="margin-right: 240px;">Giris</h3>
            <div style="border: 1px solid black;">
            <label for="username">Kullanıcı Adı</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Şifre</label>
            <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="giris" href="giris.php">Giriş</button>
        </form>

        <?php if ($mesaj): ?>
            <div class="<?= strpos($mesaj, 'Başarıyla') !== false ? 'success' : 'error' ?>">
                <?= $mesaj ?>
            </div>
        <?php endif; ?>
        <div class="limit">Limitiniz: <?= $_SESSION['giris_hakki'] ?></div>
        <button type="submit" name="logout" class="cikis">Çıkış</button>
        
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const dateStr = now.toLocaleDateString('tr-TR');
            const timeStr = now.toLocaleTimeString('tr-TR');
            document.getElementById('datetime').textContent = ` ${dateStr} -  ${timeStr}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
