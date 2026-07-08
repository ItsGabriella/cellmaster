<?php

    session_start();

    $login = $_POST["nLogin"];
    $senha = $_POST["nSenha"];

    $sql = "SELECT * FROM funcionario
            WHERE email_func = '$login'
            AND senha_func = md5('$senha')";

    include("conexaoBD.php");

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $usuario = mysqli_fetch_assoc($result);

        // Salva os dados da sessão
        $_SESSION["id"] = $usuario["idfuncionario"];
        $_SESSION["nome"] = $usuario["nome_func"];
        $_SESSION["email"] = $usuario["email_func"];

        // Verifica se marcou "Lembrar-me"
        if(isset($_POST["lembrar"])){

            setcookie(
                "lembrar_email",
                $usuario["email_func"],
                time() + (60 * 60 * 24 * 30), // 30 dias
                "/"
            );

        }else{

            setcookie(
                "lembrar_email",
                "",
                time() - 3600,
                "/"
            );

        }

        mysqli_close($conn);

        header("Location: ../home.php");
        exit;

    }else{

        mysqli_close($conn);

        header("Location: ../index.php");
        exit;

    }

?>