<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ticket-counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

<?php

include ("../../../inc/includes.php");

Html::header(
   __('Alerts', 'news'),
   $_SERVER["PHP_SELF"]
);

function getCountAllIncidents($x){
    global $DB;
    $result_by_type_1 = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `type` = $x AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
    return $DB->result($result_by_type_1, 0, 'cpt');
}

function getCountAllRequests(){
    global $DB;
    $result_by_type_2 = $DB->query("SELECT count(*) as cpt FROM `glpi_tickets` WHERE `type` = 2 AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
    return $DB->result($result_by_type_2, 0, 'cpt');
}


function getTicketsByPriority($id_type, $id_priority) {
    global $DB;
    $result = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `type` =  $id_type AND `status` = 2 AND `priority` = $id_priority");
    return $DB->result($result, 0, 'cpt');
}



// $regexPattern = `type` = 1;
//   if (preg_match($regexPattern) !== FALSE){
//         return $DB->result($result_by_type_1, 0, 'cpt');
//     }

//     elseif($DB->query($texte == "type" && $number == 1) !== FALSE){
//         return $DB->result($result_by_type_2, 0, 'cpt');
//     }

//     else {
//         echo "error";
//     }    

        $incident_count = getCountAllIncidents(1);
        echo "Incidents en cours <br>"; //$incident_count;
        echo $incident_count;
        echo "<br>";

        $demande_count = getCountAllRequests();
        echo "Demandes en cours <br>"; //$demande_count;
        echo $demande_count;
        echo "<br>";

        $x = getTicketsByPriority(1, 5);
        echo "TEST en cours ";
        var_dump ($x);


Html::footer();
