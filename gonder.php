<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Bunlar hata vermemesi için PHPMailer klasörünün içindeki dosyaları çağırır
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);

    try {
        // --- SUNUCU AYARLARI (Burayı Firma Bilgileriyle Dolduracaksın) ---
        $mail->isSMTP();
        $mail->Host       = 'mail.timpaltd.com.tr';      // Genelde mail.siteadi.com olur
        $mail->SMTPAuth   = true;
        $mail->Username   = 'web@timpaltd.com.tr';       // Gönderici maili (CPanel'den açılan)
        $mail->Password   = 'Sifre123!';                 // O mailin şifresi
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL varsa böyle kalır
        $mail->Port       = 465;                         // SSL portu genelde 465'tir
        $mail->CharSet    = 'UTF-8';                     // Türkçe karakter sorunu olmasın

        // --- ALICI VE GÖNDERİCİ AYARLARI ---
        $mail->setFrom('web@timpaltd.com.tr', 'Timpa Web Form'); // Mail kimden geliyor?
        $mail->addAddress('info@timpaltd.com.tr');               // ASIL MAİL KİME GİTSİN?

        // --- MAİL İÇERİĞİ ---
        $mail->isHTML(true);
        $mail->Subject = 'Siteden Yeni Teklif Talebi Var!';
        
        // HTML Formundaki "name" alanlarıyla eşleştirdik
        $mail->Body    = "
            <h2 style='color: #00a8cc;'>Yeni Müşteri Mesajı</h2>
            <p><b>Ad Soyad:</b> {$_POST['adsoyad']}</p>
            <p><b>Telefon:</b> {$_POST['telefon']}</p>
            <p><b>E-posta:</b> {$_POST['email']}</p>
            <p><b>Talep Edilen Ürün:</b> {$_POST['urun']}</p>
            <p><b>Müşteri Mesajı:</b><br>{$_POST['mesaj']}</p>
        ";

        $mail->send();
        // Mail gidince kullanıcıya görünecek yazı
        echo "<script>alert('Teklif talebiniz başarıyla iletildi!'); window.location.href='index.html';</script>";

    } catch (Exception $e) {
        // Hata olursa görünecek yazı
        echo "Mesaj gönderilemedi. Hata kodu: {$mail->ErrorInfo}";
    }
} else {
    // Form doldurulmadan bu sayfaya girilirse ana sayfaya atar
    header("Location: index.html");
}
?>