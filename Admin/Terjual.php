<?php include 'sidebar.php'; ?>

<h1 class="h3 mb-0">
        Data Terjual
        
    </h1>

  
<hr>
<table class="table table-striped table-sm table-bordered dt-responsive nowrap" id="table" width="100%">
  <thead>
    <tr>
      <th>No</th>
      <th>Kode_Produk</th>
      <th>Nama_Produk</th>
      <th>Harga</th>
      <th>Terjual</th>

    </tr>
  </thead>
  <tbody>
    <?php
    $no = 1;
    $data_laporan = mysqli_query($conn, "SELECT kode_produk,nama_produk,harga,sum(qty) as terjual FROM laporan GROUP BY kode_produk ORDER BY terjual DESC");
    while ($d = mysqli_fetch_array($data_laporan)) {
      $oninv = $d['invoice'];
    ?>
      <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $d['kode_produk']; ?></td>
        <td><?php echo $d['nama_produk']; ?></td>
        <td><?php echo $d['harga']; ?></td>
        <td><?php echo $d['terjual']; ?></td>
        
        
      </tr>
    <?php } ?>
  </tbody>
</table>
<?php

?>

<!-- end isinya -->
<?php include 'footer.php'; ?>