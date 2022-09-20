<?php

/**
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



use Glpi\Event;

include ("../../../inc/includes.php");

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$alert = new PluginNewsAlert();

if (isset($_POST['update'])) {
   $alert->check($_POST['id'], UPDATE);
   if ($alert->update($_POST)) {
      Event::log($_POST["id"], "PluginNewsAlert", 4, "admin",
              sprintf(__('%s updates an item'), $_SESSION["glpiname"]));
   }
   Html::back();

} else if (isset($_POST['add'])) {
   $alert->check(-1, CREATE, $_POST);
   if ($newID = $alert->add($_POST)) {
      Event::log($newID, "PluginNewsAlert", 4, "admin",
                 sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"]));

      if ($_SESSION['glpibackcreated']) {
         Html::redirect($alert->getLinkURL());
      }
   }
   Html::back();

} else if (isset($_POST['delete'])) {
   $alert->check($_POST['id'], DELETE);
   if ($alert->delete($_POST)) {
      Event::log($_POST["id"], "PluginNewsAlert", 4, "admin",
                 sprintf(__('%s deletes an item'), $_SESSION["glpiname"]));
   }
   $alert->redirectToList();

} else if (isset($_POST['restore'])) {
   $alert->check($_POST['id'], DELETE);
   if ($alert->restore($_POST)) {
      Event::log($_POST["id"], "PluginNewsAlert", 4, "admin",
                 sprintf(__('%s restores an item'), $_SESSION["glpiname"]));
   }
   Html::back();

} else if (isset($_POST['purge'])) {
   $alert->check($_POST['id'], PURGE);
   if ($alert->delete($_POST, 1)) {
      Event::log($_POST["id"], "PluginNewsAlert", 4, "admin",
                 sprintf(__('%s purges an item'), $_SESSION["glpiname"]));
   }
   $alert->redirectToList();

} else if (isset($_POST["addvisibility"])) {
   $target = new PluginNewsAlert_Target();
   $target->check(-1, CREATE, $_POST);
   $target->add($_POST);
   Html::back();
}


Html::header(
   __('Alerts', 'news'),
   $_SERVER["PHP_SELF"],
   'tools',
   "PluginNewsAlert"
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
?>

<style>
.flex { background-color: skyblue;}
.first, .second {color: darkorange; font-size: large; text-align : center;}
.first {background-color: brown;}
.second {background-color: #006573;}
</style>

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

<?php $alert->display(['id'=> $_GET["id"]]);

Html::footer();
