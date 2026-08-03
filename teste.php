<?php
session_start();
echo "<h3>Conteúdo da Sessão:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
?>