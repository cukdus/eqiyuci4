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
        }
        .bg-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; }
        .content {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            z-index: 2;
        }
        .recipient { font-size: 35px; font-weight: 700; text-transform: uppercase; margin: 60px 0 80px; }
        .kelas { font-size: 22px; font-weight: 600; text-transform: uppercase; margin: 50px 0; }
        .number { position: absolute; bottom: 40px; right: 50px; font-size: 16px; color: #ffffff !important; z-index: 3; }
    </style>
    <?php $d = $data ?? []; ?>
    </head>
<body>
    <div class="certificate-container">
        <img class="bg-img" src="assets/images/certificate-bg.png" alt="bg" />
        <div class="content">
            <div class="recipient"><?= esc($d['nama_pemilik'] ?? '') ?></div>
            <div class="kelas"><?= esc($d['nama_kelas'] ?? '') ?></div>
        </div>
        <div class="number"><?= esc($d['nomor_sertifikat'] ?? '') ?></div>
    </div>
</body>
</html>