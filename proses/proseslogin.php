<?php
    session_start();
    include "connect.php";
    $username = (isset ($_POST['username'])) ? htmlentities($_POST['username']) : "" ;
    $password = (isset ($_POST['password'])) ? htmlentities($_POST['password']) : "" ;
    $query = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' && password = '$password'");
    $hasil = mysqli_fetch_array($query);
    if($hasil){
        $_SESSION['username_elabsen'] = $username;
        $_SESSION['level_elabsen'] = $hasil['level'];
        header('location:../home.php');
    }else{ ?>
        <script>
            alert("Username atau password yang anda masukkan SALAH !!!");
            window.location="../index.html"
        </script>
<?php   
    }
?>
