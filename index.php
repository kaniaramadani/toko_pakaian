<?php

require_once 'Database.php';

$db = Database::connect();


// ======================================
// CLASS USER (PARENT CLASS)
// ======================================

class User {

    public $nama;
    protected $username;
    private $password;

    public function __construct($nama, $username, $password) {

        $this->nama = $nama;
        $this->username = $username;
        $this->password = $password;
    }

    public function tampilkanUser() {

        return [
            'nama' => $this->nama,
            'username' => $this->username
        ];
    }
}


// ======================================
// CLASS ADMIN (CHILD CLASS)
// ======================================

class Admin extends User {

    public function informasiAdmin() {

        return $this->tampilkanUser();
    }
}


// ======================================
// CLASS PRODUK (PARENT CLASS)
// ======================================

class Produk {

    public $namaProduk;
    protected $harga;
    private $stok;

    public function __construct($namaProduk, $harga, $stok) {

        $this->namaProduk = $namaProduk;
        $this->harga = $harga;
        $this->stok = $stok;
    }

    public function tampilkanProduk() {

        return [
            'nama' => $this->namaProduk,
            'harga' => $this->harga,
            'stok' => $this->stok
        ];
    }

    public function getStok() {

        return $this->stok;
    }

    public static function namaToko() {

        return "FASHION STORE";
    }
}


// ======================================
// CLASS PAKAIAN (CHILD CLASS)
// ======================================

class Pakaian extends Produk {

    public $ukuran;
    public $warna;

    public function __construct(
        $namaProduk,
        $harga,
        $stok,
        $ukuran,
        $warna
    ) {

        parent::__construct($namaProduk, $harga, $stok);

        $this->ukuran = $ukuran;
        $this->warna = $warna;
    }

    public function tampilkanPakaian() {

        return [
            'produk' => $this->tampilkanProduk(),
            'ukuran' => $this->ukuran,
            'warna' => $this->warna
        ];
    }
}


// ======================================
// MEMBUAT OBJECT
// ======================================

$admin = new Admin(
    "Kania",
    "admin",
    "admin123"
);

$pakaian = new Pakaian(
    "Kaos Polos",
    50000,
    20,
    "L",
    "Hitam"
);

$dataAdmin = $admin->informasiAdmin();

$dataPakaian = $pakaian->tampilkanPakaian();


// ======================================
// MENGAMBIL DATA DARI DATABASE
// ======================================

$result = $db->query("
    SELECT produk.*, kategori.nama_kategori
    FROM produk
    INNER JOIN kategori
    ON produk.id_kategori = kategori.id_kategori
");


// Menghitung jumlah produk

$totalProduk = $db->query(
    "SELECT COUNT(*) AS total FROM produk"
)->fetch_assoc()['total'];


// Menghitung jumlah pelanggan

$totalPelanggan = $db->query(
    "SELECT COUNT(*) AS total FROM pelanggan"
)->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fashion Store</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #fff5f8;
            color: #444;
        }

        /* HEADER */

        header {
            background: linear-gradient(135deg, #db2777, #f472b6);
            color: white;
            padding: 30px 45px;
            box-shadow: 0 4px 15px rgba(219,39,119,0.2);
        }

        header h1 {
            font-size: 30px;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        header p {
            font-size: 14px;
        }

        /* CONTAINER */

        .container {
            width: 90%;
            max-width: 1300px;
            margin: 35px auto;
        }

        /* WELCOME */

        .welcome {
            background: white;
            padding: 30px;
            border-radius: 18px;
            margin-bottom: 30px;
            border-left: 6px solid #ec4899;
            box-shadow: 0 5px 20px rgba(236,72,153,0.08);
        }

        .welcome h2 {
            color: #db2777;
            margin-bottom: 12px;
        }

        .welcome p {
            margin-bottom: 5px;
            color: #777;
        }

        /* DASHBOARD */

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
            margin-bottom: 35px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(236,72,153,0.08);
            border-bottom: 5px solid #f472b6;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            color: #999;
            font-size: 15px;
            margin-bottom: 15px;
        }

        .card h2 {
            color: #db2777;
            font-size: 26px;
        }

        .icon {
            font-size: 28px;
            margin-bottom: 12px;
        }

        /* SECTION */

        .section {
            background: white;
            padding: 30px;
            border-radius: 18px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(236,72,153,0.08);
            overflow-x: auto;
        }

        .section h2 {
            color: #db2777;
            margin-bottom: 25px;
            font-size: 21px;
        }

        /* TABLE */

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th {
            background: #ec4899;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #fce7f3;
        }

        tr:hover {
            background: #fff1f7;
        }

        .badge {
            background: #fce7f3;
            color: #be185d;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        /* FOOTER */

        footer {
            background: #fce7f3;
            text-align: center;
            padding: 25px;
            color: #be185d;
            margin-top: 40px;
        }

        @media (max-width: 600px) {

            header {
                padding: 25px;
            }

            .container {
                width: 94%;
            }

            .section {
                padding: 20px;
            }

        }

    </style>

</head>

<body>

    <!-- HEADER -->

    <header>

        <h1>FASHION STORE ♡</h1>

        <p>Sistem Informasi Penjualan Toko Pakaian</p>

    </header>


    <div class="container">

        <!-- WELCOME -->

        <div class="welcome">

            <h2>
                Selamat Datang,
                <?= htmlspecialchars($dataAdmin['nama']); ?>! ♡
            </h2>

            <p>
                Username:
                <?= htmlspecialchars($dataAdmin['username']); ?>
            </p>

            <p>
                Selamat bekerja dan mengelola Fashion Store.
            </p>

        </div>


        <!-- DASHBOARD CARD -->

        <div class="cards">

            <div class="card">

                <div class="icon">👩🏻‍💻</div>

                <h3>Nama Admin</h3>

                <h2>
                    <?= htmlspecialchars($dataAdmin['nama']); ?>
                </h2>

            </div>


            <div class="card">

                <div class="icon">👗</div>

                <h3>Total Produk</h3>

                <h2><?= $totalProduk; ?></h2>

            </div>


            <div class="card">

                <div class="icon">🛍️</div>

                <h3>Total Pelanggan</h3>

                <h2><?= $totalPelanggan; ?></h2>

            </div>


            <div class="card">

                <div class="icon">💗</div>

                <h3>Contoh Produk OOP</h3>

                <h2>
                    <?= htmlspecialchars($dataPakaian['produk']['nama']); ?>
                </h2>

            </div>

        </div>


        <!-- INFORMASI PRODUK OOP -->

        <div class="section">

            <h2>♡ Informasi Produk OOP</h2>

            <table>

                <tr>
                    <th>Nama Produk</th>
                    <th>Ukuran</th>
                    <th>Warna</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>

                <tr>

                    <td>
                        <?= htmlspecialchars($dataPakaian['produk']['nama']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($dataPakaian['ukuran']); ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($dataPakaian['warna']); ?>
                    </td>

                    <td>
                        Rp <?= number_format(
                            $dataPakaian['produk']['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>
                    </td>

                    <td>
                        <?= $pakaian->getStok(); ?>
                    </td>

                </tr>

            </table>

        </div>


        <!-- DATA PRODUK DATABASE -->

        <div class="section">

            <h2>♡ Data Produk Fashion Store</h2>

            <table>

                <tr>

                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Ukuran</th>
                    <th>Warna</th>
                    <th>Harga</th>
                    <th>Stok</th>

                </tr>

                <?php if ($result && $result->num_rows > 0): ?>

                    <?php $no = 1; ?>

                    <?php while ($row = $result->fetch_assoc()): ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <?= htmlspecialchars($row['nama_produk']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama_kategori']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['ukuran']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['warna']); ?>
                            </td>

                            <td>
                                Rp <?= number_format(
                                    $row['harga'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>
                            </td>

                            <td>
                                <span class="badge">
                                    <?= $row['stok']; ?> tersedia
                                </span>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">
                            Belum ada data produk.
                        </td>
                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </div>


    <!-- FOOTER -->

    <footer>

        <p>
            ♡ 2026 Fashion Store ♡
        </p>

        <p>
            Sistem Informasi Penjualan Toko Pakaian
        </p>

    </footer>

</body>

</html>