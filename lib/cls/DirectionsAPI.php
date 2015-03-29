<?php
/**
 * Created by PhpStorm.
 * User: Ricky
 * Date: 3/27/2015
 * Time: 9:55 PM
 */

class DirectionsAPI {

    public function sendRequest($slon, $slat, $dlon,$dlat)
    {
        // Bus
        $params1 = array(
            'origin' => $slon . ',' . $slat,
            'destination' => $dlon . ',' . $dlat,
            'sensor' => 'true',
            'units' => 'imperial',
            'mode' => 'transit'
        );

        // Walk
        $params2 = array(
            'origin' => $slon . ',' . $slat,
            'destination' => $dlon . ',' . $dlat,
            'sensor' => 'true',
            'units' => 'imperial',
            'mode' => 'walking'
        );

        $params_string1 = "";
        $params_string2 = "";

        // Join parameters into URL string
        foreach ($params1 as $var => $val) {
            $params_string1 .= '&' . $var . '=' . urlencode($val);
        }
        foreach ($params2 as $var => $val) {
            $params_string2 .= '&' . $var . '=' . urlencode($val);
        }

        // Request URL
        $url1 = "http://maps.googleapis.com/maps/api/directions/json?" . ltrim($params_string1, '&');
        $url2 = "http://maps.googleapis.com/maps/api/directions/json?" . ltrim($params_string2, '&');

        //echo $url1;
        //echo $url2;
        $content1 = file_get_contents($url1);
        $content2 = file_get_contents($url2);

        $directions1 = json_decode($content1);
        $directions2 = json_decode($content2);

        if($directions1->status != "OK"){
            echo "{\"status\": \"BAD\",\"reason\": \"$directions1->status\"}";
            exit;
        }
        if($directions2->status != "OK"){
            echo "{\"status\": \"BAD\",\"reason\": \"$directions2->status\"}";
            exit;
        }

        $busNum = "-1";

        try {
            $busNum = $directions1->routes[0]->legs[0]->steps->steps->line->short_name;
        }
        catch (Exception $e) {
            $busNum = "-1";
        }

        echo "<p> $busNum </p>";
//        foreach($directions1->routes[0]->legs[0]->steps as $step) {
//            foreach($step->duration as $time){
//                //$transit = $transit + $time;
//            }
//        }


        $transitDuration = $directions1->routes[0]->legs[0]->duration->value;
        $walkingDuration = $directions2->routes[0]->legs[0]->duration->value;

        //Does walking take less time?
        if($walkingDuration <= $transitDuration){
            echo "{\"status\": \"OK\",\"preferred_method\":\"walk\",\"walk_duration\":$walkingDuration,\"bus_duration\":$transitDuration}";
        }else{
            echo "{\"status\": \"OK\",\"preferred_method\":\"bus\",\"walk_duration\":$walkingDuration,\"bus_duration\":$transitDuration}";
        }
    }
}