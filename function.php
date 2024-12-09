<?php
session_start();

// koneksi ke database
$conn = mysqli_connect("localhost","root","","stockbarang");


// untuk menambahkan barang baru di Stock Barang
if (isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    
    $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock) values('$namabarang', '$deskripsi','$stock')");
    if ($addtotable){
        header('location:index.php');
    }   else {
        echo 'Gagal';
        header('location:index.php');
    }
}


// menambahkan barang masuk
if (isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstockbarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, keterangan, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if ($addtomasuk&&$updatestockmasuk){
        header('location:masuk.php');
    }   else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}


// menambahkan barang keluar
if (isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstockbarang = mysqli_query($conn,"select * from stock where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstockbarang);

    $stocksekarang = $ambildatanya['stock'];


    if($stocksekarang >= $qty){
        // kalau barangnya cukup
    $tambahkanstocksekarangdenganquantity = $stocksekarang-$qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar (idbarang, penerima, qty) values('$barangnya','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update stock set stock='$tambahkanstocksekarangdenganquantity' where idbarang='$barangnya'");
    if ($addtokeluar&&$updatestockmasuk){
        header('location:keluar.php');
    }   else {
        echo 'Gagal';
        header('location:keluar.php');
    }

} else{ 
    // kalau barangnya gak cukup
    echo '
    <script>
        alert("Stock saat ini tidak mencukupi");
        window.location.href="keluar.php";
    </script>
    ';
}
}



// Update Info Barang Di Stock Barang
if (isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn,"update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
    if ($update){
        header('location:index.php');
    }   else {
        echo 'Gagal';
        header('location:index.php');
    }
}


// Menghapus Barang Dari Stock Barang
if (isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn,"delete from stock where idbarang='$idb'");
    if ($hapus){
        header('location:index.php');
    }   else {
        echo 'Gagal';
        header('location:index.php');
    }
}



// Mengubah / Edit Data di Barang Masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg; // Kuantiti Bertambah
        $newStock = $stockskrg + $selisih;
    } else {
        $selisih = $qtyskrg - $qty; // Kuantiti Berkurang
        $newStock = $stockskrg - $selisih;
    }

    $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$newStock' WHERE idbarang='$idb'");
    $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$deskripsi' WHERE idmasuk='$idm'");

    if ($kurangistocknya && $updatenya) {
        header('Location: masuk.php');
    } else {
        echo 'Gagal';
        header('Location: masuk.php');
    }
}



// Menghapus Data Barang Di Barang Masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok - $qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}


// Mengubah Data Barang keluar
if (isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
            $selisih = $qty-$qtyskrg;
            $kurangin = $stocksekarang + $selisih;
            $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");
            $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
                if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
                }   else {
                    echo 'Gagal';
                    header('location:keluar.php');
            }
        
    } else {
            $selisih = $qtyskrg-$qty;
            $kurangin = $stocksekarang+$selisih;
            $kurangistocknya = mysqli_query($conn,"update stock set stock='$kurangin' where idbarang='$idb'");
            $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
                if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
                }   else {
                    echo 'Gagal';
                    header('location:keluar.php');
            }
    }

}



// menghapus barang di barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn,"select * from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stok = $data['stock'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn,"update stock set stock='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn,"delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}


// menambah admin baru

if(isset($_POST['addadmin'])){
     $email = $_POST['email'];
     $password = $_POST['password'];

     $queryinsert = mysqli_query($conn,"insert into login (email, password) values ('$email','$password')");

     if($queryinsert){
        // berhasil
        header('location:admin.php');

     } else {
        // gagal
        header('location:admin.php');
     }


}


// edit admin
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$passwordbaru' WHERE iduser='$idnya'");

    if ($queryupdate) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}


// hapus admin
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "DELETE from login WHERE iduser='$id'");

    if ($querydelete) {
        header('location:admin.php');
    } else {
        header('location:admin.php');
    }
}


?>