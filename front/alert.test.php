<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ticket-counter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
.first, .second {color: black; font-size: large;}
</style>

</head>
<body>

<?php

include ("../../../inc/includes.php");

Html::header(
   __('Alerts', 'news'),
   $_SERVER["PHP_SELF"]
);

function getCountAllIncidents(){
    global $DB;
    $result_by_type_1 = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `type` = 1 AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
    return $DB->result($result_by_type_1, 0, 'cpt');
}

function getCountAllRequests(){
    global $DB;
    $result_by_type_2 = $DB->query("SELECT count(*) as cpt FROM `glpi_tickets` WHERE `type` = 2 AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
    return $DB->result($result_by_type_2, 0, 'cpt');
}
function getCountAll($my_type){
    $id_type = 0;
    if ($my_type == "incidents")
    {
        $id_type = 1;
    }
    elseif ($my_type == "demandes")
    {
        $id_type = 2;
    };

    global $DB;
    $result = $DB->query("SELECT count(*) as cpt FROM `glpi_tickets` WHERE `type` = $id_type AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
    return $DB->result($result, 0, 'cpt');
}

$incidents = getCountAll("incidents");
$demande = getCountAll("demandes");
// $regexPattrn = `type` = 1;
//   if (preg_match($regexPattern) !== FALSE){
//         return $DB->result($result_by_type_1, 0, 'cpt');
//     }

//     elseif($DB->query($texte == "type" && $number == 1) !== FALSE){
//         return $DB->result($result_by_type_2, 0, 'cpt');
//     }

//     else {
//         echo "error";
//     }    
    // return $DB->result($result_by_type_1, 0, 'cpt');
    // return $DB->result($result_by_type_2, 0, 'cpt');
?>


<div class = 'flex'>
    <div class ='first'>
        <?php
        $incident_count = getCountAllIncidents();
        echo "Incidents en cours <br>"; //$incident_count;
        echo $incident_count;
        ?>
    </div> 

    <div class ='second'>
        <?php 
        $demande_count = getCountAllRequests();
        echo "Demandes en cours <br>"; //$demande_count;
        echo $demande_count;
        ?>
    </div>
</div>

<?php
// function dooble_count(){
//         $id_of_incident = 1;
//         $id_of_request = 2;
//         global $DB;
//         $request_count = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `type` = $id_of_request AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
//         $incident_count = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `type` = $id_of_incident AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
//         if ($text == 'id_of_incident') {
//             return $DB->result($incident_count, 0, 'cpt');
//         } elseif ($id_of_request) {
//             return $DB->result($request_count, 0, 'cpt');
//         };

// }

// $incident = dooble_count();
// echo $incident;
// $request = dooble_count();
// echo $request;
// <!-- PERfORMANCE -->

function getTicketsByPerformance($id_type, $id_priority, $quantityOfDays) {
  global $DB, $week_start, $week_end;
  $result = $DB->query("SELECT COUNT(*) as spt FROM `glpi_tickets` WHERE `date` BETWEEN '$week_start' AND '$week_end' AND datediff(solvedate, date_creation) = $quantityOfDays AND `type` = $id_type AND `priority` = $id_priority");
  return $DB->result($result, 0, 'cpt');
}

function countAndInsertPerformance ($id_type, $diffdate) {
  for ($id_priority = ID_PRIORITY_MAX; $id_priority >= 1; $id_priority--) {
    $numberByPriority = getTicketsByPerformance($id_type, $id_priority);
    if ($diffdate = 1) {
      $quantityOfDays = '<=1';
    }
    elseif ($diffdate = 2){
      $quantityOfDays = '<= 3'; 
      // '> 1 and <= 3'
    }
    elseif ($diffdate = 3){
      $quantityOfDays = '> 3';
    }
    $quantityOfDays = array(
        "<=1" => 1,
        "> 1 and <= 3" => 2,
        "> 3" => 3
    );
    //php genere le html table
    echo '<td scope="col">';
    echo $numberByPriority;
    echo '</td>';
  }
}
?>
<div id="table">
  <h1>Performance</h1>
  <h3 class="text-right"><?php echo ' La semaine de '.$week_start.' à '.$week_end;?></h3>
<table class="table table-striped">
  <thead>
  <tr class="bg-success">
    <th class="bg-info"></th>
    <th class="bg-info" scope="col"><h5>Temps de traitement</h5></th>  
    <th class="bg-info" colspan="6"><h5 style>Priorité</h5></th>
  </tr>
    <tr>
      <th scope="col">#</th>
      <th scope="col">#</th> 
      <th scope="col">Très haute</th>
      <th scope="col">Haute</th>
      <th scope="col">Moyenne</th>
      <th scope="col">Basse</th>
      <th scope="col">Très basse</th>
    </tr>
  </thead>
    <tr>
      <th class='display-6' rowspan ="4">Incidents</th>
      <th scope="row">Moins d'un jour</th>
      <?php countAndInsertPerformance(1, 1);?>
    </tr>
    <tr> 
      <th scope="row">Entre 1 et 3 jours</th>
      <?php countAndInsertPerformance(1, 2);?></th>
    </tr>
    <tr>
      <th scope="row">Plus de 3 jours</th>
      <?php countAndInsertPerformance(1, 3);?>
    </tr>
    <tr>
      <th scope="row">En Backlog</th>
      <td></th>
    </tr>
    <tr>
      <th class='display-6' rowspan ="4">Demandes</th>
      <th scope="row">Moins d'un jour</th>
      <td></th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th scope="row">Entre 1 et 3 jours</th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th scope="row">Plus de 3 jours</th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <th scope="row">En Backlog</th>
      <td></td>
      <td></td>
      <td></td>
      <td></th>
      <td></td>
    </tr>
  </tbody>
</table>
</div>