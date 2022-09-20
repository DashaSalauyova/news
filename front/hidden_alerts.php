

<!-- /**
 * -------------------------------------------------------------------------
 * News plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of News.
 *
 * News is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * News is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with News. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2015-2022 by News plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/news
 * -------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

if ($_SESSION["glpiactiveprofile"]["interface"] != "central") {
   Html::helpHeader(__('Alerts', 'news'), $_SERVER['PHP_SELF'], $_SESSION["glpiname"]);
} else {
   Html::header(
      __('Alerts', 'news'),
      $_SERVER["PHP_SELF"],
      'tools',
      "PluginNewsAlert"
   );
}

PluginNewsAlert::displayAlerts(['show_only_login_alerts' => false,
                                'show_hidden_alerts' => true]);

Html::footer(); -->

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

if ($_SESSION["glpiactiveprofile"]["interface"] != "central") {
    Html::helpHeader(__('Alerts', 'news'), $_SERVER['PHP_SELF'], $_SESSION["glpiname"]);
 } else {
    Html::header(
       __('Alerts', 'news'),
       $_SERVER["PHP_SELF"],
       'tools',
       "PluginNewsAlert"
    );
 }

Html::header(
   __('Alerts', 'news'),
   $_SERVER["PHP_SELF"]
);

PluginNewsAlert::displayAlerts(['show_only_login_alerts' => false,
                                'show_hidden_alerts' => true]);

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
?>


<?= Html::footer(); ?>


