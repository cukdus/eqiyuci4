<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? ('Sertifikat - ' . ($data['nomor_sertifikat'] ?? ''))) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .certificate-container {
            width: 1024px;
            height: 768px;
            margin: 20px auto;
            padding: 40px;
            background-color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
            background-image: url('<?= base_url('assets/images/certificate-bg.png') ?>');
            background-size: cover;
            background-position: center;
        }
        .certificate-content { text-align: center; margin: 200px 0; }
        .recipient-name {
            font-size: 50px; color: #000; margin: 120px 0 20px 220px;
            font-family: 'Teko', sans-serif; text-transform: uppercase;
            display: inline-block; padding: 0 50px 5px;
        }
        .certificate-class { font-size: 24px; font-weight: bold; text-transform: uppercase; margin: 20px 0 0 210px; color: #000; }
        .certificate-number { position: absolute; bottom: 15px; right: 35px; font-size: 22px; font-family: 'Rubik', sans-serif; color: #000; display: inline-block; transform: rotate(90deg); transform-origin: bottom right; }
        .download-button { position: fixed; bottom: 20px; right: 20px; z-index: 1000; }
        @media print { body { background: none; } .certificate-container { box-shadow: none; margin: 0; padding: 40px; } .download-button { display: none; } }
    </style>
    <?php /* Data binding */ $d = $data ?? []; ?>
</head>
<body>
    <div class="certificate-container" id="certificate">
        <div class="certificate-content">
            <h2 class="recipient-name"><?= esc($d['nama_pemilik'] ?? '') ?></h2>
            <p class="certificate-class"><?= esc($d['nama_kelas'] ?? '') ?></p>
        </div>
        <div class="certificate-number"><?= esc($d['nomor_sertifikat'] ?? '') ?></div>
    </div>

    <a href="<?= base_url('admin/sertifikat/' . (int) ($d['id'] ?? 0) . '/download') ?>" class="btn btn-success download-button">Download Sertifikat</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
