<?php
$host = 'localhost';
$dbname = 'users'; 
$user = 'root';          
$password = '';           

// Criando a conexão
$conn = new mysqli($host, $user, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
