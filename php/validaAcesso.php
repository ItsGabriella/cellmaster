<?php

    $login = $_POST["nLogin"];
    $senha = $_POST["nSenha"];

    //echo $_POST["nLogin"]. ' - '.$_POST["nSenha"];
    $sql = "SELECT * FROM `funcionario` WHERE email_func = '$login' AND senha_func = md5('$senha');";

    //conectar ao banco
    include("conexaoBD.php");

    // var_dump ($conn);
    // die();
    $result = mysqli_query($conn, $sql);

    // var_dump ($result);
    // die();

    mysqli_close($conn);

    if (mysqli_num_rows($result) > 0){
        header("location:../home.php");
    } else{
        header("location:../");
        // var_dump("ACESSO NEGADO");
    }

    die();



?>

