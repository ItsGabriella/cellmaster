<?php
    //idfuncionario, nome_func, cargos_idcargos, tel_func, email_func
    //nFuncionario, nTelefone, nmail, nID

    //nCliente, nEndereco, nCPF, nTelefone, nmail
    //	idcliente	nome_clien	endereco_clien	cpf_clien	tel_clien	email_clien
    include('funcoes.php');

    $cliente = $_POST["nCliente"];
    $endereco  = $_POST["nEndereco"];
    $CPF  = $_POST["nCPF"];
    $tel = $_POST["nTelefone"];
    $mail  = $_POST["nmail"];
    $idCliente = isset($_GET["IDClien"]) ? $_GET["IDClien"] : 0;
    $funcao = isset($_GET["funcao"]) ? $_GET["funcao"] : "";

    include("conexaoBD.php");

    if($funcao == "I"){
        $idCliente = proxIdCliente();
        $sql = "INSERT INTO cliente (idcliente, nome_clien, endereco_clien, cpf_clien, tel_clien, email_clien)
        VALUES(".$idCliente.",
        '".$cliente."',
        '".$endereco."',
        '".$CPF."',
        '".$tel."',
        '".$mail."'
        );";

    }elseif($funcao == "U"){
        $sql = "UPDATE cliente  SET
        nome_clien = '".$cliente."',
        endereco_clien = '".$endereco."',
        cpf_clien = '".$CPF."',
        tel_clien = '".$tel."',
        email_clien = '".$mail."'
        WHERE idcliente = ".$idCliente.";";

    }elseif($funcao == "D"){
        $sql = "DELETE FROM cliente "
                ." WHERE idcliente = ".$idCliente.";";
    }

    $result = mysqli_query($conn,$sql);
    mysqli_close($conn);

    header("location: ../clientes.php");

?>