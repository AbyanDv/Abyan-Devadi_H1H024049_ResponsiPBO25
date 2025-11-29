Nama : Abyan Devadi
NIM : H1H024049
Shift Awal : B
Shift Akhir : C


Penjelasan Aplikasi Pokemon Training :

-Pokemon.php :

File ini adalah tempat struktur class Pokemon. Ada dua class utama di sini.

Class pertama adalah Pokemon yang dibuat abstract. Class ini cuma jadi abstract class, tidak bisa langsung dipakai. Di dalamnya ada properti yang semua Pokemon harus punya: nama, tipe, level, HP, dan jurus spesial. Semua properti ini dibuat private supaya tidak bisa diubah sembarangan dari luar class abstrack, harus lewat getter dan setter. class ini juga ada 2 method turunan yang harus dipakai : specialMove() dan train(). Jadi nanti setiap tipe Pokemon bisa punya cara training dan jurus yang berbeda, tapi strukturnya tetap sama.

Class kedua adalah TipeRumput yang isinya Pokemon Vileplume. Ini turunan dari class Pokemon tadi. Di constructor-nya langsung dikasih nama Vileplume, tipe Grass/Poison, level 5, HP 100, attack 75 dan empat jurus spesialnya. Method train() di sini yang penting karena ngatur gimana Pokemon jadi lebih kuat. Rumusnya gini: setiap 20 poin intensitas nambah 1 level, tiap 1 poin intensitas nambah 1.05 HP, tiap 1 poin intensitas nambah 0.7 attack. Terus ada bonus tergantung jenis training:

    Latihan Attack : Level : +1, Attack: +3, HP : +7. 
    Latihan Defense : Level : +2, HP :+ 13.
    Latihan Speed : HP : +6.

Setelah training selesai, method ini return array yang isinya data sebelum dan sesudah training, jadi bisa diliat berapa naiknya.

Konsep OOP semua terpakai :
Enkapsulasi -> data dan methode dijadikan satu dalam sebuah class / objek. 
Abstraksi -> menyembunyikan detail kompleks biar ga ribet untuk interaksi/bagian interface.
Turunan -> Okemon Vileplume contoh dari inheritancenya (pakai extends pokemon).
Polimorfisme -> satu fungsi bisa buat banyak keluaran / digunakan di berbagai class turunannya dengan output berbeda namun tetap punya struktur yang sama (SpecialMove adalah contohnya)

-Index.php:

File ini yang ngatur semua tampilan dan logika aplikasi web-nya. Pertama, dia start session buat nyimpen data Pokemon dan riwayat training. Pokemon disimpen dalam bentuk serialize supaya objektnya utuh waktu disimpan di session. Kalau mau dipakai lagi tinggal unserialize.

Aplikasinya punya tiga halaman yang diatur pakai parameter GET. Halaman beranda nampilin status Pokemon lengkap dengan card warna hijau. Dari sini user bisa mulai training, lihat riwayat, atau reset Pokemon. Halaman training isinya form yang minta user pilih jenis training sama intensitasnya (1-100). Waktu form disubmit, sistem panggil method train() dari Pokemon, terus simpan hasilnya ke array riwayat di session. Abis itu redirect ke halaman training lagi sambil nampilin alert sukses. Halaman riwayat nampilin semua history training dari yang terbaru. Tiap item riwayat ditampilin dalam card yang ada detail lengkapnya: jenis training, intensitas, perubahan level, attack dan HP, sama pesan bonusnya.

Yang penting dari file ini adalah dia bisa jaga data Pokemon tetap ada selama user buka aplikasi, terus semua perubahan dari training kesimpen rapi. Ada juga error handling kalau misalnya ada masalah waktu load Pokemon dari session, otomatis bikin Pokemon baru. Fitur reset juga dikasih konfirmasi dulu biar user tidak kehapus datanya tanpa sengaja.

