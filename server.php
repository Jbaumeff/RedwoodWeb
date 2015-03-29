<?php
/**
 * Created by PhpStorm.
 * User: Ricky
 * Date: 3/27/2015
 * Time: 9:55 PM
 */

require "lib/cls/DirectionsAPI.php";
$good = true;
$request = new DirectionsAPI();

if(!isset($_REQUEST['MAGIC']) || $_REQUEST['MAGIC'] != "CB5D56A21FE65143") {
    echo "{\"status\": \"BAD\",\"reason\": \"WRONG_MAGIC\"}";
}else {

    if (isset($_REQUEST['slon'])) {
        $slon = $_REQUEST['slon'];
    } else {
        $good = false;
    }

    if (isset($_REQUEST['slat'])) {
        $slat = $_REQUEST['slat'];
    } else {
        $good = false;
    }

    if (isset($_REQUEST['dlon'])) {
        $dlon = $_REQUEST['dlon'];
    } else {
        $good = false;
    }

    if (isset($_REQUEST['dlat'])) {
        $dlat = $_REQUEST['dlat'];
    } else {
        $good = false;
    }

    if (!$good) {
        echo "{\"status\": \"BAD\",\"reason\": \"MISSING_PARAMS\"}";
    }

    //Send the request to the server
    $request->sendRequest($slat,$slon,$dlat,$dlon);
}
?>


