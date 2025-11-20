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
        .content { position: absolute; top: 52mm; left: 0; right: 0; z-index: 2; }
        .recipient { font-size: 10mm; font-weight: 700; text-transform: uppercase; text-align: center; margin: 47mm 0 6mm 55mm; color: #000; }
        .kelas { font-size: 6mm; font-weight: 600; text-align: center; text-transform: uppercase; margin: 16mm 0 0 55mm; color: #000; }
        .number { position: absolute; bottom: 5.6mm; right: 15.6mm; font-size: 5.8mm; color: #000; z-index: 3; display: inline-block; transform: rotate(90deg); transform-origin: bottom right; }
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