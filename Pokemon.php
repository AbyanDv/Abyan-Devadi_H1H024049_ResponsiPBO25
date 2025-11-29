<?php
abstract class Pokemon {
    private $nama;
    private $tipe;
    private $level;
    private $hp;
    private $jurusSpesial;
    
    public function __construct($nama, $tipe, $level, $hp, $jurusSpesial) {
        $this->nama = $nama;
        $this->tipe = $tipe;
        $this->level = $level;
        $this->hp = $hp;
        $this->jurusSpesial = $jurusSpesial;
    }

    abstract public function specialMove();
    
    abstract public function train($jenisTraining, $intensitas);
    
    public function getNama() {
        return $this->nama;
    }
    
    public function getTipe() {
        return $this->tipe;
    }
    
    public function getLevel() {
        return $this->level;
    }
    
    public function getHp() {
        return $this->hp;
    }
    
    public function getJurusSpesial() {
        return $this->jurusSpesial;
    }
    
    public function setLevel($level) {
        $this->level = $level;
    }
    
    public function setHp($hp) {
        $this->hp = $hp;
    }
}

class TipeRumput extends Pokemon {
    public function __construct() {
        parent::__construct("Vileplume", "Grass/Poison", 5, 75, "Petal Dance, Solar Beam, Sludge Bomb, Giga Drain");
    }
    
    public function specialMove() {
        $moves = [
            "Petal Dance",
            "Solar Beam",
            "Sludge Bomb",
            "Giga Drain"
        ];
        return implode(", ", $moves);
    }
    
    public function train($jenisTraining, $intensitas) {
        $levelSebelum = $this->getLevel();
        $hpSebelum = $this->getHp();
        
        $peningkatanLevel = floor($intensitas / 20);
        $peningkatanHp = $intensitas * 2;
        
        $bonusMessage = "";
        
        if ($jenisTraining === "Attack") {
            $peningkatanLevel += 2;
            $peningkatanHp += 25;
            $bonusMessage = "Bonus Attack training. Level +2, HP +25.";
        } elseif ($jenisTraining === "Defense") {
            $peningkatanLevel += 1;
            $peningkatanHp += 15;
            $bonusMessage = "Bonus Defense training. Level +1, HP +15.";
        } elseif ($jenisTraining === "Speed") {
            $peningkatanHp += 8;
            $bonusMessage = "Bonus Speed training. HP +8.";
        }
        
        $this->setLevel($this->getLevel() + $peningkatanLevel);
        $this->setHp($this->getHp() + $peningkatanHp);
        
        return [
            'levelSebelum' => $levelSebelum,
            'levelSesudah' => $this->getLevel(),
            'hpSebelum' => $hpSebelum,
            'hpSesudah' => $this->getHp(),
            'pesan' => $bonusMessage
        ];
    }
}
?>