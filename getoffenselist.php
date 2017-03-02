<?php
require_once('sys/config/config.php');


    $sql0 = "SELECT distinct( offense ) FROM police_daily";
    error_log( '$sql0= ' . $sql0 . "\n", 3, "/tmp/php_error.log" );

    try {
        $rs0 = $dbConnect->query( $sql0 );
        $results = $rs0->fetchAll(PDO::FETCH_ASSOC);
        header('Content-type:application/json');
        exit(json_encode($results)); 
    } catch(PDOException $ex) {
        echo "Database error" . $ex->getMessage(); //user friendly message
    }
