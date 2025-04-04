<?php
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici = $_POST["username"];
    $sifre = $_POST["password"];

    if ($_SESSION['giris_hakki'] > 0) {
        if ($kullanici !== $dogru_kullanici) {
            $mesaj = "❌ Kullanıcı adı hatalıdır!";
            $_SESSION['giris_hakki']--;
        } elseif ($sifre !== $dogru_sifre) {
            $mesaj = "❌ Şifre hatalıdır!";
            $_SESSION['giris_hakki']--;
        } else {
            $mesaj = "✅ Başarıyla giriş yaptınız!";
            $_SESSION['giris_hakki'] = 3; // Başarılı giriş sonrası sıfırla
        }
    } else {
        $mesaj = "⛔ Giriş hakkınız doldu!";
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
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            text-align: left;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .datetime {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .limit {
            margin-top: 10px;
            font-weight: bold;
            color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Giriş Yap</h2>
        <form method="POST" action="">
            <label for="username">Kullanıcı Adı</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Şifre</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">🔒 Giriş</button>
        </form>

        <?php if ($mesaj): ?>
            <div class="<?= strpos($mesaj, 'Başarıyla') !== false ? 'success' : 'error' ?>">
                <?= $mesaj ?>
            </div>
        <?php endif; ?>

        <div class="limit">Limitiniz: <?= $_SESSION['giris_hakki'] ?></div>

        <div class="datetime" id="datetime"></div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const dateStr = now.toLocaleDateString('tr-TR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const timeStr = now.toLocaleTimeString('tr-TR');
            document.getElementById('datetime').textContent = `📅 ${dateStr} - 🕒 ${timeStr}`;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime(); // İlk yüklemede çağır
    </script>
</body>
</html>
