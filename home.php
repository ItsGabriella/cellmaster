<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Home</title>
        <meta charset="UTF-8">
        <!-- <link rel="stylesheet" href="css/stylelogin.css"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="css/stylehome.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    </head>

    <body>
        <nav id="sidebar">
            <div class="sidebar_content">
                <div id="user">
                    <img src="img/perfil.png" id="user_avatar" alt="Avatar">

                    <p id="user_infos">
                        <span class="item-description">
                            Fulano de tal
                        </span>
                        <span class="item-description">
                            Gerente
                        </span>
                    </p>

                </div>

                <ul id="side_items">
                    <li class="side-item active">
                        <a href="#">
                            <i class="fa-solid fa-chart-line"></i>
                            <span class="item-description">
                                Dashboard
                            </span>

                        </a>
                    </li>

                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span class="item-description">
                                Funcionários
                            </span>

                        </a>
                    </li>

                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-users"></i>
                            <span class="item-description">
                                Clientes
                            </span>

                        </a>
                    </li>


                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span class="item-description">
                                Orçamento
                            </span>

                        </a>
                    </li>


                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-file-contract"></i>
                            <span class="item-description">
                                Ordem de Serviço
                            </span>

                        </a>
                    </li>


                    <li class="side-item">
                        <a href="#">
                            <i class="fa-solid fa-file"></i>
                            <span class="item-description">
                                Relatório
                            </span>

                        </a>
                    </li>

                </ul>

                <button id="open_btn">
                    <i id="open_btn_icon" class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <div id="logout">
                <button id="logout_btn" onclick="window.location.href='index.php';"> 
                    <i class="fa-solid fa-arrow-right-from-bracket" ></i>
                    <span class="item-description" >
                        Logout
                    </span>
                </button>
            </div>
        </nav>


        <script src="js/home.js"></script>


    </body>

</html>