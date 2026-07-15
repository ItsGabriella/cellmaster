<?php
    //idfuncionario, nome_func, cargos_idcargos, tel_func, email_func
    //nFuncionario, nTelefone, nmail, nID
    include('funcoes.php');

    $funcionario = $_POST["nFuncionario"];
    $cargo  = $_POST["ncargo"];
    $tel = $_POST["nTelefone"];
    $mail  = $_POST["nmail"];
    $idFuncionario = isset($_GET["IDFunc"]) ? $_GET["IDFunc"] : 0;
    $funcao = isset($_GET["funcao"]) ? $_GET["funcao"] : "";

    include("conexaoBD.php");

    if($funcao == "I"){
        $idFuncionario = proxIdFuncionario();
        $sql = "INSERT INTO funcionario (idfuncionario, nome_func, cargos_idcargos, tel_func, email_func)
        VALUES(".$idFuncionario.",
        '".$funcionario."',
        '".$cargo."',
        '".$tel."',
        '".$mail."'
        );";

    }elseif($funcao == "U"){
        $sql = "UPDATE funcionario  SET
        nome_func = '".$funcionario."',
        cargos_idcargos = '".$cargo."',
        tel_func = '".$tel."',
        email_func = '".$mail."'
        WHERE idfuncionario = ".$idFuncionario.";";

    }elseif($funcao == "D"){
        $sql = "DELETE FROM funcionario "
                ." WHERE idfuncionario = ".$idFuncionario.";";
    }

    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    header("location: ../funcionarios.php");

?>