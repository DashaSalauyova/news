<?php
function getCountAll($id_type){
    global $DB;
    $result = $DB->query("SELECT count(*) as cpt FROM `glpi_tickets` WHERE `type` = $id_type AND `status` <>6 and `status` <>5 AND `is_deleted` <>1");
    return $DB->result($result, 0, 'cpt');
}
?>

<div class = 'flex'>
   <div class ='first'>
       <?php
       $incident_count = getCountAll(1);
       echo "Incidents en cours <br>"; //$incident_count;
       echo $incident_count;
       ?>
   </div> 

   <div class ='second'>
       <?php 
       $demande_count = getCountAll(2);
       echo "Demandes en cours <br>"; //$demande_count;
       echo $demande_count;
       ?>
   </div>
</div>