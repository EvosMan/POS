<?php include 'sidebar.php'; ?>
<a href="Terjual.php"><button class="btn btn-primary btn-sm border-0 float-right" >Data Terjual</button></a>
<!-- isinya -->
<?php
$i1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(qty) as totqty FROM laporan"));
$i2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(subtotal) as totdpt FROM laporan"));
$i3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(subtotal-qty*harga_modal) as totdpt1 FROM laporan"));
$i4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(subtotal) as isub FROM laporan"));
?>
<h1 class="h3 mb-2">Laporan</h1>
<div class="row">

  <div class="col-6 col-sm-6 col-md-3 col-lg-3 m-pr-1 m-mb-1">
    <div class="box-laporan">
      <a href="Terjual.php">
      <p class="small mb-0">Terjual</p>
      <h5 class="mb-0"><?php echo ribuan($i1['totqty']); ?></h5>
      </a>
    </div>
  </div>

  <!-- <div class="col-6 col-sm-6 col-md-3 col-lg-3 m-pl-1 m-mb-1">
                <div class="box-laporan">
                    <p class="small mb-0">Pendapatan</p>
                    <h5 class="mb-0">Rp.<?php echo ribuan($i3['totdpt1']); ?></h5>
                </div>
            </div> -->

  <!-- <div class="col-6 col-sm-6 col-md-3 col-lg-3 m-pr-1">
                <div class="box-laporan">
                    <p class="small mb-0">Penjualan</p>
                    <h5 class="mb-0">Rp.<?php echo ribuan($i2['totdpt']); ?></h5>
                </div>
            </div> -->

  <div class="col-6 col-sm-6 col-md-3 col-lg-3 m-pl-1">
    <div class="box-laporan">
      <p class="small mb-0">Total</p>
      <h5 class="mb-0">Rp.<?php echo ribuan($i4['isub']); ?></h5>
    </div>
  </div>

</div>
<hr>
<table class="table table-striped table-sm table-bordered dt-responsive nowrap" id="table" width="100%">
  <thead>
    <tr>
      <th>No</th>
      <th>Invoice</th>
      <th>Qty</th>
      <th>SubTotal</th>
      <th>Pembayaran</th>
      <th>Kembalian</th>
      <th>Outlet</th>
      <th>Tanggal</th>
      
    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    $data_laporan = mysqli_query($conn, "SELECT * FROM invoice i, kasir k WHERE i.userid = k.userid AND i.status='selesai' ORDER BY i.invid DESC");
    while ($d = mysqli_fetch_array($data_laporan)) {
      $oninv = $d['invoice'];
    ?>
      <tr>
        <td><?php echo $no++; ?></td>
        <td><a href="invoice.php?detail=<?php echo $oninv ?>"><?php echo $d['invoice']; ?></a></td>
        <td><?php
            $result1 = mysqli_query($conn, "SELECT SUM(qty) AS count FROM laporan WHERE invoice='$oninv'");
            $cekrow = mysqli_num_rows($result1);
            $row1 = mysqli_fetch_assoc($result1);
            $count = $row1['count'];
            if ($cekrow > 0) {
              echo ribuan($count);
            } else {
              echo '0';
            } ?></td>
        <td>Rp.<?php
                $result2 = mysqli_query($conn, "SELECT SUM(subtotal) AS count1 FROM laporan WHERE invoice='$oninv'");
                $cekrow1 = mysqli_num_rows($result2);
                $row2 = mysqli_fetch_assoc($result2);
                $count1 = $row2['count1'];
                if ($cekrow1 > 0) {
                  echo ribuan($count1);
                } else {
                  echo '0';
                } ?></td>
        <td>Rp.<?php echo ribuan($d['pembayaran']); ?></td>
        <td>Rp.<?php echo ribuan($d['kembalian']); ?></td>
        <td><?php echo $d['toko']; ?></td>
        <td><?php echo $d['tgl_inv']; ?></td>
        
      </tr>
    <?php } ?>
  </tbody>
</table>
<?php

?>

<!-- end isinya -->
<?php include 'footer.php'; ?>