<?php
    function dbConnect(){
        $host = 'localhost';
        $db = 'taller_motocicletas';
        $user = 'root';
        $pwd = '';
        $conexion = null;

        try {
            $conexion = new PDO(
                'mysql:host='.$host.';dbname='.$db.';charset=utf8',
                $user,
                $pwd,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch (PDOException $e) {
            echo '<p>No se puede conectar con la base de datos!!</p>';
            exit;
        }
        return $conexion;
    }
    $conexion = dbConnect();
?>
