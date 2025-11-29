<?php
abstract class Pokemon {
    private $nama;
    private $tipe;
    private $level;
    private $attack;
    private $hp;
    private $jurusSpesial;
    
    public function __construct($nama, $tipe, $level, $attack, $hp, $jurusSpesial) {
        $this->nama = $nama;
        $this->tipe = $tipe;
        $this->level = $level;
        $this->attack = $attack;
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

    public function getAttack() {
        return $this->attack;
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

    public function setAttack($attack) {
        $this->attack = $attack;
    }   
    
    public function setHp($hp) {
        $this->hp = $hp;
    }
}

class TipeRumput extends Pokemon {
    public function __construct() {
        parent::__construct("Vileplume", "Grass/Poison", 5, 75, 100, "Petal Dance");
    }
    
    public function specialMove() {
        return "Petal Dance";
    }
    
    public function train($jenisTraining, $intensitas) {
        $levelSebelum = $this->getLevel();
        $hpSebelum = $this->getHp();
        $attackSebelum = $this->getAttack();
        
        $peningkatanLevel = floor($intensitas / 20);
        $peningkatanHp = floor($intensitas * 1.05);
        $peningkatanAttack = floor($intensitas * 0.7);
        $bonusMessage = "";
        
        if ($jenisTraining === "Attack") {
            $peningkatanLevel += 1;
            $peningkatanHp += 7;
            $peningkatanAttack += 3;
            $bonusMessage = "Bonus Attack training. Level +2, HP +25.\n Peningkatan level : {$peningkatanLevel} (include bonus), Peningkatan HP : {$peningkatanHp} (include bonus), Peningkatan Attack : {$peningkatanAttack} (include bonus)";
        } elseif ($jenisTraining === "Defense") {
            $peningkatanLevel += 2;
            $peningkatanHp += 14    ;
            $bonusMessage = "Bonus Defense training. Level +1, HP +15.\n Peningkatan level : {$peningkatanLevel} (include bonus), Peningkatan HP : {$peningkatanHp} (include bonus), Peningkatan Attack : {$peningkatanAttack}";
        } elseif ($jenisTraining === "Speed") {
            $peningkatanHp += 6;
            $bonusMessage = "Bonus Speed training. HP +8.\n Peningkatan level : {$peningkatanLevel}, Peningkatan HP : {$peningkatanHp} (include bonus), Peningkatan Attack : {$peningkatanAttack}";
        }
        
        $this->setLevel($this->getLevel() + $peningkatanLevel);
        $this->setHp($this->getHp() + $peningkatanHp);
        $this->setAttack($this->getAttack() + $peningkatanAttack);
        
        return [
            'levelSebelum' => $levelSebelum,
            'levelSesudah' => $this->getLevel(),
            'hpSebelum' => $hpSebelum,
            'hpSesudah' => $this->getHp(),
            'attackSebelum' => $attackSebelum,
            'attackSesudah' => $this->getAttack(),
            'pesan' => $bonusMessage
        ];
    }
}
?>