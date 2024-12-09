<?php

// jika belum login, maka harus login terlebih dahulu
// jika sudah login harus logout untuk kembali ke tampilan login

if(isset($_SESSION['log'])){

} else {
    header('location:login.php');
}

?>