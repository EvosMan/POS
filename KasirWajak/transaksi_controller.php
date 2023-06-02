<?php
include_once './config.php';

if (isset($_POST['import'])) {
    $noinv = htmlspecialchars($_POST['id_invoice']);
    $Ipembayaran = htmlspecialchars($_POST['pembayaran']);
    $Ikembalian = htmlspecialchars($_POST['kembalian']);
    $data_penjualan = mysqli_query($conn, 'SELECT * FROM penjualan');

    while ($d = mysqli_fetch_array($data_penjualan)) {

        $idproduk = $d['kode_produk'];
        $data_produk = mysqli_query($conn, "SELECT * FROM inventory WHERE kode_produk = '$idproduk'");
        $data_produk = mysqli_fetch_assoc($data_produk);
        $sisa = (int) $data_produk['stok'] - (int) $d['qty'];
        mysqli_query($conn, "UPDATE inventory SET stok = $sisa WHERE kode_produk = '$idproduk'");
    }
    session_start();

    $userid = $_SESSION['userid'];
    $UpdLap = mysqli_query($conn, "INSERT INTO laporan (invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal)
       SELECT invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal FROM penjualan") or die(mysqli_connect_error());

    $UpdCart = mysqli_query($conn, "UPDATE invoice SET
        userid='$userid',pembayaran='$Ipembayaran',kembalian='$Ikembalian',status='selesai' WHERE invoice='$noinv'")
        or die(mysqli_connect_error());

    $DelCart = mysqli_query($conn, "DELETE FROM penjualan") or die(mysqli_connect_error());

    if ($UpdCart && $UpdLap && $DelCart) {
        echo '<script>window.location="invoice.php?detail=' . $noinv . '"</script>';
    } else {
        echo '<script>alert("Gagal Di Simpan");history.go(-1);</script>';
    }
};

if (isset($_POST['InputCart'])) {
    $Input1 = htmlspecialchars($_POST['Ckdproduk']);
    $Input2 = htmlspecialchars($_POST['Cnproduk']);
    $Input3 = htmlspecialchars($_POST['Charga']);
    $Input5 = htmlspecialchars($_POST['Csubs']);
    $hrg_m = htmlspecialchars($_POST['harga_modal']);


    $cekDulu = mysqli_query($conn, "SELECT * FROM penjualan ");
    $liat = mysqli_num_rows($cekDulu);
    $f = mysqli_fetch_array($cekDulu);
    $inv_c = $f['invoice'];
    $ii = htmlspecialchars($_POST['Cqty']);

    if ($liat > 0) {
        $cekbrg = mysqli_query($conn, "SELECT * FROM penjualan WHERE kode_produk='$Input1' and invoice='$inv_c'");
        $liatlg = mysqli_num_rows($cekbrg);
        $brpbanyak = mysqli_fetch_array($cekbrg);
        $jmlh = $brpbanyak['qty'];
        $jmlh1 = $brpbanyak['harga'];

        if ($liatlg > 0) {
            $i = htmlspecialchars($_POST['Cqty']);
            $baru = $jmlh + $i;
            $baru1 = $jmlh1 * $baru;

            $updateaja = mysqli_query($conn, "UPDATE penjualan SET qty='$baru', subtotal='$baru1' WHERE invoice='$inv_c' and kode_produk='$Input1'");
            $upstok = mysqli_query($conn, "UPDATE penjualan SET qty='$sisa' WHERE id='$id_brg'");
            if ($updateaja) {
                echo '<script>window.location="index.php"</script>';
            } else {
                echo '<script>window.location="index.php"</script>';
            }
        } else {
            $tambahdata = mysqli_query($conn, "INSERT INTO penjualan (invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal)
         values('$inv_c','$Input1','$Input2','$Input3','$hrg_m','$ii','$Input5')");
            if ($tambahdata) {
                echo '<script>window.location="index.php"</script>';
            } else {
                echo '<script>window.location="index.php"</script>';
            }
        };
    } else {

        $queryStar = mysqli_query($conn, "SELECT max(invoice) as kodeTerbesar FROM invoice");
        $data = mysqli_fetch_array($queryStar);
        $kodeInfo = $data['kodeTerbesar'];
        $urutan = (int) substr($kodeInfo, 8, 2);
        $urutan++;
        $huruf = "AD";
        $oi = $huruf . date("jnyGi") . sprintf("%02s", $urutan);

        $bikincart = mysqli_query($conn, "INSERT INTO invoice (invoice,pembayaran,kembalian,status) values('$oi','','','proses')");
        if ($bikincart) {
            $tambahuser = mysqli_query($conn, "INSERT INTO penjualan (invoice,kode_produk,nama_produk,harga,harga_modal,qty,subtotal)
        values('$oi','$Input1','$Input2','$Input3','$hrg_m','$ii','$Input5')");
            if ($tambahuser) {
                echo '<script>window.location="index.php"</script>';
            } else {
                echo '<script>window.location="index.php"</script>';
            }
        } else {
        }
    }
};
