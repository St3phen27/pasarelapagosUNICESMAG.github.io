<?php
class database{
    static function conectar(){
        $serverName = "STEPHEN-PC\SQLEXPRESS";
        $connectionInfo = array( "Database"=>"pasarelapagosUNICESMAG", "UID"=>"unicesmag", "PWD"=>"12345678");
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {
            echo "Conexión establecida.<br />";
       }else{
            echo "Conexión no se pudo establecer.<br />";
            die( print_r( sqlsrv_errors(), true));
       }
       
       return $conn;
    }
}
?>