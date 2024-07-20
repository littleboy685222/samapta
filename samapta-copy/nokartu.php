<?php
    include "koneksi.php";

    $sql=mysqli_query($konek, "select * from tmprfid");
    $data=mysqli_fetch_array($sql);
    
    $nokartu=$data['nokartu'];
?>

<div class="form-group">
    <label>No.Kartu</label>
    <input type="text" name="nokartu" id="nokartu" required placeholder="Tempelkan Kartu RFID" class="form-control" style="width:200px"
    value="<?php echo $nokartu; ?>">
</div>