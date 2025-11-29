<?php
session_start();
require_once 'pokemon.php';

if (!isset($_SESSION['pokemon'])) {
    $_SESSION['pokemon'] = serialize(new TipeRumput());
    $_SESSION['riwayatTraining'] = [];
}

try {
    $pokemon = unserialize($_SESSION['pokemon']);
} catch (Exception $e) {
    $_SESSION['pokemon'] = serialize(new TipeRumput());
    $_SESSION['riwayatTraining'] = [];
    $pokemon = unserialize($_SESSION['pokemon']);
}

//halaman
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 'beranda';

// Proses form Training
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['training'])) {
    $jenisTraining = $_POST['jenisTraining'];
    $intensitas = (int)$_POST['intensitas'];

    $hasil = $pokemon->train($jenisTraining, $intensitas);

    $_SESSION['riwayatTraining'][] = [
        'jenisTraining' => $jenisTraining,
        'intensitas' => $intensitas,
        'levelSebelum' => $hasil['levelSebelum'],
        'levelSesudah' => $hasil['levelSesudah'],
        'hpSebelum' => $hasil['hpSebelum'],
        'hpSesudah' => $hasil['hpSesudah'],
        'pesan' => $hasil['pesan'],
        'waktu' => date('d/m/Y H:i:s')
    ];

    $_SESSION['pokemon'] = serialize($pokemon);

    header('Location: index.php?halaman=training&sukses=1');
    exit;
}

// Reset Pokemon
if (isset($_GET['reset'])) {
    $_SESSION['pokemon'] = serialize(new TipeRumput());
    $_SESSION['riwayatTraining'] = [];
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Training</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            background-color: #4caf50;
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 36px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 16px;
        }

        .content {
            background-color: white;
            padding: 30px;
            border: 1px solid #ddd;
        }

        .pokemon-card {
            background-color: #e8f5e9;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid #4caf50;
        }

        .pokemon-card h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 28px;
            text-align: center;
        }

        .pokemon-card h3 {
            color: #2e7d32;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            margin-bottom: 8px;
            background-color: white;
            border-left: 3px solid #4caf50;
        }

        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #2e7d32;
            font-weight: bold;
        }

        .special-move {
            margin-top: 20px;
            padding: 15px;
            background-color: #4caf50;
            color: white;
        }

        .special-move strong {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: bold;
            flex: 1;
            min-width: 140px;
        }

        .btn-primary {
            background-color: #4caf50;
            color: white;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            background-color: #757575;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #616161;
        }

        .btn-danger {
            background-color: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            background-color: #d4edda;
            color: #155724;
        }

        .alert p {
            margin-top: 10px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2e7d32;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #4caf50;
        }

        .riwayat-item {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .riwayat-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4caf50;
        }

        .riwayat-header h3 {
            color: #2e7d32;
            font-size: 18px;
            margin: 0;
        }

        .riwayat-waktu {
            background-color: #4caf50;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
        }

        .riwayat-detail {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .riwayat-detail div {
            background-color: white;
            padding: 10px;
            border-left: 3px solid #4caf50;
        }

        .riwayat-detail strong {
            color: #555;
            font-size: 12px;
            display: block;
            margin-bottom: 5px;
        }

        .riwayat-detail .value {
            color: #2e7d32;
            font-size: 14px;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state h3 {
            color: #757575;
            margin-bottom: 10px;
            font-size: 22px;
        }

        .empty-state p {
            color: #9e9e9e;
            margin-bottom: 20px;
            font-size: 16px;
        }

        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 28px;
            text-align: center;
        }

        .bonus-message {
            margin-top: 15px;
            padding: 12px;
            background-color: #c8e6c9;
            color: #1b5e20;
            border-left: 3px solid #4caf50;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PokeCare</h1>
            <p>Pokemon Training Center</p>
        </div>

        <div class="content">
            <?php if ($halaman === 'beranda'): ?>

                <div class="pokemon-card">
                    <h2><?php echo $pokemon->getNama(); ?></h2>

                    <div class="info-row">
                        <span class="info-label">Tipe Pokemon:</span>
                        <span class="info-value"><?php echo $pokemon->getTipe(); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Level:</span>
                        <span class="info-value"><?php echo $pokemon->getLevel(); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">HP:</span>
                        <span class="info-value"><?php echo $pokemon->getHp(); ?></span>
                    </div>

                    <div class="special-move">
                        <strong>Special Moves:</strong>
                        <p><?php echo $pokemon->specialMove(); ?></p>
                    </div>
                </div>

                <div class="button-group">
                    <a href="?halaman=training" class="btn btn-primary">Start Training</a>
                    <a href="?halaman=riwayat" class="btn btn-secondary">Training History</a>
                    <a href="?reset=1" class="btn btn-danger" onclick="return confirm('Reset Vileplume?')">Reset Pokemon</a>
                </div>

            <?php elseif ($halaman === 'training'): ?>

                <h2>Training Session</h2>

                <?php if (isset($_GET['sukses'])): ?>
                    <div class="alert">
                        Training berhasil!
                        <?php 
                        if (!empty($_SESSION['riwayatTraining'])) {
                            $trainingTerakhir = end($_SESSION['riwayatTraining']);
                            echo "<p>" . $trainingTerakhir['pesan'] . "</p>";
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <div class="pokemon-card">
                    <h3>Status Pokemon</h3>
                    <div class="info-row">
                        <span class="info-label">Nama:</span>
                        <span class="info-value"><?php echo $pokemon->getNama(); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Level:</span>
                        <span class="info-value"><?php echo $pokemon->getLevel(); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">HP:</span>
                        <span class="info-value"><?php echo $pokemon->getHp(); ?></span>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="jenisTraining">Jenis Training:</label>
                        <select name="jenisTraining" id="jenisTraining" required>
                            <option value="">Pilih Jenis Training</option>
                            <option value="Attack">Attack Training</option>
                            <option value="Defense">Defense Training</option>
                            <option value="Speed">Speed Training</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="intensitas">Intensitas (1-100):</label>
                        <input type="number" name="intensitas" id="intensitas" min="1" max="100" placeholder="Masukkan nilai 1-100" required>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="training" class="btn btn-primary">Mulai Training</button>
                        <a href="?halaman=beranda" class="btn btn-secondary">Kembali</a>
                        <a href="?halaman=riwayat" class="btn btn-secondary">Lihat Riwayat</a>
                    </div>
                </form>

            <?php elseif ($halaman === 'riwayat'): ?>

                <h2>Riwayat Training</h2>

                <?php if (empty($_SESSION['riwayatTraining'])): ?>

                    <div class="empty-state">
                        <h3>Belum Ada Riwayat</h3>
                        <p>Mulai training untuk melihat riwayat.</p>
                        <a href="?halaman=training" class="btn btn-primary">Mulai Training</a>
                    </div>

                <?php else: ?>

                    <?php foreach (array_reverse($_SESSION['riwayatTraining']) as $index => $riwayat): ?>
                        <div class="riwayat-item">
                            <div class="riwayat-header">
                                <h3>Training #<?php echo count($_SESSION['riwayatTraining']) - $index; ?></h3>
                                <span class="riwayat-waktu"><?php echo $riwayat['waktu']; ?></span>
                            </div>

                            <div class="riwayat-detail">
                                <div>
                                    <strong>Jenis Training:</strong>
                                    <span class="value"><?php echo $riwayat['jenisTraining']; ?></span>
                                </div>
                                <div>
                                    <strong>Intensitas:</strong>
                                    <span class="value"><?php echo $riwayat['intensitas']; ?></span>
                                </div>
                                <div>
                                    <strong>Level:</strong>
                                    <span class="value"><?php echo $riwayat['levelSebelum']; ?> => <?php echo $riwayat['levelSesudah']; ?></span>
                                </div>
                                <div>
                                    <strong>HP:</strong>
                                    <span class="value"><?php echo $riwayat['hpSebelum']; ?> => <?php echo $riwayat['hpSesudah']; ?></span>
                                </div>
                            </div>

                            <?php if (!empty($riwayat['pesan'])): ?>
                                <div class="bonus-message">
                                    <?php echo $riwayat['pesan']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="button-group">
                        <a href="?halaman=beranda" class="btn btn-secondary">Kembali</a>
                        <a href="?halaman=training" class="btn btn-primary">Training Lagi</a>
                    </div>

                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</body>
</html>