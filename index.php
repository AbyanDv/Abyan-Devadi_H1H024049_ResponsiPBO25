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

$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 'beranda';

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
        'attackSebelum' => $hasil['attackSebelum'],
        'attackSesudah' => $hasil['attackSesudah'],
        'pesan' => $hasil['pesan'],
        'waktu' => date('d/m/Y H:i:s')
    ];

    $_SESSION['pokemon'] = serialize($pokemon);

    header('Location: index.php?halaman=training&sukses=1');
    exit;
}

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
    <title>Pokemon Training - PokeCare</title>
    <link rel="stylesheet" href="style.css">
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
                    <div class="pokemon-header">
                        <img src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/45.png" 
                             alt="Vileplume" 
                             class="pokemon-image">
                        <h2><?php echo $pokemon->getNama(); ?></h2>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Tipe Pokemon</span>
                        <span class="info-value"><?php echo $pokemon->getTipe(); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Level</span>
                        <span class="info-value"><?php echo $pokemon->getLevel(); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">HP</span>
                        <span class="info-value"><?php echo $pokemon->getHp(); ?></span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Attack</span>
                        <span class="info-value"><?php echo $pokemon->getAttack(); ?></span>
                    </div>

                    <div class="special-move">
                        <strong>Special Move</strong>
                        <p><?php echo $pokemon->specialMove(); ?></p>
                    </div>
                </div>

                <div class="button-group">
                    <a href="?halaman=training" class="btn btn-primary">Start Training</a>
                    <a href="?halaman=riwayat" class="btn btn-secondary">Training History</a>
                    <a href="?reset=1" class="btn btn-danger" onclick="return confirm('Reset Vileplume ke kondisi awal?')">Reset Pokemon</a>
                </div>

            <?php elseif ($halaman === 'training'): ?>

                <h2>Training Session</h2>

                <?php if (isset($_GET['sukses'])): ?>
                    <div class="alert">
                        Training berhasil diselesaikan.
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
                        <span class="info-label">Nama</span>
                        <span class="info-value"><?php echo $pokemon->getNama(); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Level</span>
                        <span class="info-value"><?php echo $pokemon->getLevel(); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">HP</span>
                        <span class="info-value"><?php echo $pokemon->getHp(); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Attack</span>
                        <span class="info-value"><?php echo $pokemon->getAttack(); ?></span>
                    </div>
                </div>

                <div class="special-moves-info">
                    <h4>Special Move</h4>
                    <div class="move-item">
                        <strong>Petal Dance</strong>
                        <p>Vileplume melepaskan pusaran kelopak bunga yang menyerang lawan secara terus-menerus, memanfaatkan kekuatan dan racun alami dari kelopaknya.</p>
                    </div>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="jenisTraining">Jenis Training</label>
                        <select name="jenisTraining" id="jenisTraining" required>
                            <option value="">Pilih Jenis Training</option>
                            <option value="Attack">Attack Training</option>
                            <option value="Defense">Defense Training</option>
                            <option value="Speed">Speed Training</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="intensitas">Intensitas</label>
                        <div class="slider-container">
                            <div class="slider-value" id="sliderValue">50</div>
                            <input type="range" name="intensitas" id="intensitas" min="1" max="100" value="50">
                        </div>
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
                                <h3>Training Ke-<?php echo count($_SESSION['riwayatTraining']) - $index; ?></h3>
                                <span class="riwayat-waktu"><?php echo $riwayat['waktu']; ?></span>
                            </div>

                            <div class="riwayat-detail">
                                <div>
                                    <strong>Jenis Training</strong>
                                    <span class="value"><?php echo $riwayat['jenisTraining']; ?></span>
                                </div>
                                <div>
                                    <strong>Intensitas</strong>
                                    <span class="value"><?php echo $riwayat['intensitas']; ?></span>
                                </div>
                                <div>
                                    <strong>Level</strong>
                                    <span class="value"><?php echo $riwayat['levelSebelum']; ?> → <?php echo $riwayat['levelSesudah']; ?></span>
                                </div>
                                <div>
                                    <strong>HP</strong>
                                    <span class="value"><?php echo $riwayat['hpSebelum']; ?> → <?php echo $riwayat['hpSesudah']; ?></span>
                                </div>
                                <div>
                                    <strong>Attack</strong>
                                    <span class="value"><?php echo $riwayat['attackSebelum']; ?> → <?php echo $riwayat['attackSesudah']; ?></span>
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

    <script>
        // Update slider value display
        const slider = document.getElementById('intensitas');
        const sliderValue = document.getElementById('sliderValue');
        
        if (slider && sliderValue) {
            slider.addEventListener('input', function() {
                sliderValue.textContent = this.value;
            });
        }
    </script>
</body>
</html>