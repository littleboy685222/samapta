<!-- menu.php -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="#" class="navbar-brand">SAMAPTA</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li class="<?php echo $current_page == 'datakadet.php' ? 'active' : ''; ?>"><a href="datakadet.php">Data Kadet Mahasiswa</a></li>
            <li class="<?php echo $current_page == 'rekap.php' ? 'active' : ''; ?>"><a href="rekap.php">Rekap Hasil Samapta</a></li>
            <li class="<?php echo $current_page == 'lari.php' ? 'active' : ''; ?>"><a href="lari.php">Lari 12 m</a></li>
            <li class="<?php echo $current_page == 'pullup.php' ? 'active' : ''; ?>"><a href="pullup.php">Pull Up</a></li>
            <li class="<?php echo $current_page == 'pushup.php' ? 'active' : ''; ?>"><a href="pushup.php">Push Up</a></li>
            <li class="<?php echo $current_page == 'situp.php' ? 'active' : ''; ?>"><a href="situp.php">Sit Up</a></li>
            <li class="<?php echo $current_page == 'shuttlerun.php' ? 'active' : ''; ?>"><a href="shuttlerun.php">Shuttle Run</a></li>
            <li class="<?php echo $current_page == 'renang.php' ? 'active' : ''; ?>"><a href="renang.php">Renang</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
