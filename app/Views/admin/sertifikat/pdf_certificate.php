<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? ('Sertifikat - ' . ($data['nomor_sertifikat'] ?? ''))) ?></title>
    <style>
        /* Layout A4 landscape: 297mm x 210mm */
        @page { size: A4 landscape; margin: 0; }
        html, body { margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; }
        .certificate-container {
            width: 100%;
            height: 210mm;
            position: relative;
            background-image: url('assets/images/certificate-bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .content {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
        }
        .recipient { font-size: 45px; font-weight: 700; text-transform: uppercase; margin-bottom: 60px; }
        .kelas { font-size: 22px; font-weight: 600; text-transform: uppercase; }
        .number { position: absolute; bottom: 35px; right: 50px; font-size: 20px; color: #fff; }
    </style>
    <?php $d = $data ?? []; ?>
    </head>
<body>
    <div class="certificate-container">
        <div class="content">
            <div class="recipient"><?= esc($d['nama_pemilik'] ?? '') ?></div>
            <div class="kelas"><?= esc($d['nama_kelas'] ?? '') ?></div>
        </div>
        <div class="number"><?= esc($d['nomor_sertifikat'] ?? '') ?></div>
    </div>
</body>
</html>