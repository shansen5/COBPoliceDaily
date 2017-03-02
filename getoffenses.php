<?php
require_once('sys/config/config.php');

// get the q parameter from URL
if ( isset( $_REQUEST['offense'])) {
    $offense = $_REQUEST["offense"];

    $sql0 = "SELECT id, CAST( offense_time AS character ) as off_time, offense, description, ";
    $sql0 .= "lat, lon FROM police_daily";
    $where_started = false;
    if ( $offense !== 'ALL') {
        $sql0 .= " WHERE offense = '" . $offense . "'";
        $where_started = true;
    }    
    if ( isset( $_REQUEST['start_date'] )) {
        $start_date = $_REQUEST['start_date'];
        if ( ! $where_started ) {
            $sql0 .= " WHERE";
            $where_started = true;
        } else {
            $sql0 .= " AND"; 
        }
        $sql0 .= " offense_time >= '" . $start_date . "'";
    }
    if ( isset( $_REQUEST['end_date'] )) {
        $end_date = $_REQUEST['end_date'];
        if ( ! $where_started ) {
            $sql0 .= " WHERE";
        } else {
            $sql0 .= " AND"; 
        }
        $sql0 .= " offense_time <= '" . $end_date . "'";
    }
    $sql0 .= " ORDER by lat,lon";
    if ( isset( $_REQUEST['limit'] )) {
        $limit = $_REQUEST['limit'];
        $sql0 .= " LIMIT " . $limit;
    }
    error_log( '$sql0= ' . $sql0 . "\n", 3, "/tmp/php_error.log" );

    try {
        $rs0 = $dbConnect->query( $sql0 );
        $results = $rs0->fetchAll(PDO::FETCH_ASSOC);
        foreach ( $results as $rw ) {
            $rw['description'] = substr( $rw['description'], 0, 20 );
        }
        header('Content-type:application/json');
        exit(json_encode($results)); 
    } catch(PDOException $ex) {
        echo "Database error" . $ex->getMessage(); //user friendly message
    }
}
