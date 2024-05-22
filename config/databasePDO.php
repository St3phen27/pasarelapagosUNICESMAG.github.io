<?php
class database{
    static function conectar(){
        $server = "localhost";
        $database = "pasarelapagosUNICESMAG";
        $username = "unicesmag";
        $password = "12345678";

        try {
            $conn = new PDO("sqlsrv:server=$server;Database=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Conexión establecida sqlsrvPDO.<br />";
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
            return $conn;
    }
}
?>