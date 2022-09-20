<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    
<?php
    
include ("../../../inc/includes.php");

Html::header(
   __('Alerts', 'news'),
   $_SERVER["PHP_SELF"]
);


const ID_PRIORITY_MAX=5;
$day = date('w');
$week_start = date('Y-m-d', strtotime('-'.$day.' days + 3 day'));

$week_start = date('Y-m-d', strtotime($week_start.' - 1 week'));

$week_end = date('Y-m-d', strtotime($week_start.' + 6 days'));

$this_week = ' La semaine de '.$week_start.' à '.$week_end;//afficher la date

// echo 'start : '.$week_start.'  end :'.$week_end;

function getTicketsByPriority($id_type, $id_priority) {
    global $DB, $week_start, $week_end;
    $result = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `date` BETWEEN '$week_start' AND '$week_end' AND `type` = $id_type AND `status` <>5 AND status <>6 AND `priority` = $id_priority AND `is_deleted` <>1");
    return $DB->result($result, 0, 'cpt');
}

function countAndInsertPriority($id_type) {
  for ($id_priority = ID_PRIORITY_MAX; $id_priority >= 1; $id_priority--) {
    $numberByPriority = getTicketsByPriority($id_type, $id_priority);
    //php genere le html table
    echo '<td scope="col">';
    echo $numberByPriority;
    echo '</td>';
  }
}
?>


<div id="table">
  <h1>Tickets à traiter</h1>
  <h3 class="text-right"><?php echo $this_week?></h3>
<table class="table table-striped table-dark">
  <thead>
  <tr>
    <th class="bg-info" scope="col">&nbsp;</th>  
    <th class="bg-info" colspan="5"><h5>Priorité par type</h5></th>
  </tr>
    <tr>
      <th scope="col">#</th> 
      <th scope="col">Très haute</th>
      <th scope="col">Haute</th>
      <th scope="col">Moyenne</th>
      <th scope="col">Basse</th>
      <th scope="col">Très basse</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="col">Incidents</th>
      <?php countAndInsertPriority(1); ?>
    </tr>
    <tr>
      <th scope="col">Demandes</th>
      <?php countAndInsertPriority(2);?>
    </tr>
  </tbody>
</table>
</div>
<!-- PERfORMANCE -->

<?php
function getTicketsByPerformance($id_type, $id_priority, $dayCountMin, $dayCountMax) {
  global $DB, $week_start, $week_end;
      //LA REQUETE SQL POUR le DELAIS DE TRAITEMENT 
  $result = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `date` BETWEEN '$week_start' 
  AND '$week_end' AND datediff(`solvedate`, `date_creation`) BETWEEN $dayCountMin AND $dayCountMax AND `type` = $id_type AND `priority` = $id_priority");
    //< 1 nombre de tickets resolus en moins d'un jour
    //( < 3 pour le traitement en moins de trois jour ; 3 < and > 1 pour le delai de traitement entre 1 et 3 jours);
  return $DB->result($result, 0, 'cpt');
}

function countAndInsertPerformance($id_type, $dayCountMin, $dayCountMax) {
  for ($id_priority = ID_PRIORITY_MAX; $id_priority >= 1; $id_priority--) {
    $numberByPerformance = getTicketsByPerformance($id_type, $id_priority, $dayCountMin, $dayCountMax);
    echo '<td scope="col">';
    echo $numberByPerformance;
    echo '</td>';
  }
}

function getBacklog($id_type, $id_priority){
  global $DB;
  $result = $DB->query("SELECT COUNT(*) as cpt FROM `glpi_tickets` WHERE `type` = $id_type AND `priority` = $id_priority AND `status` <>5 AND status <>6 AND `priority` = $id_priority AND `is_deleted` <>1");
  return $DB->result($result, 0, 'cpt');
}

function countAndInsertBacklog($id_type) {
  for ($id_priority = ID_PRIORITY_MAX; $id_priority >= 1; $id_priority--) {
    $numberByPerformance = getBacklog($id_type, $id_priority);
    echo '<td scope="col">';
    echo $numberByPerformance;
    echo '</td>';
  }
}
var_dump(countAndInsertPerformance(1, 0, 1));
var_dump(countAndInsertPerformance(1, 1, 3));
var_dump(countAndInsertPerformance(2, 1, 3));
var_dump(countAndInsertPerformance(1, 1, 7));
?>
<div id="table">
  <h1>Performance</h1>
  <h3 class="text-right"><?php echo $this_week?></h3>
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
      <?php countAndInsertPerformance(1, 0, 1)?>
    </tr>
    <tr> 
      <th scope="row">Entre 1 et 3 jours</th>
      <?php countAndInsertPerformance(1, 1, 3)?>
    </tr>
    <tr>
      <th scope="row">Plus de 3 jours</th>
      <?php countAndInsertPerformance(1, 3, 7)?>
    </tr>
    <tr>
      <th scope="row">En Backlog</th>
      <?php countAndInsertBacklog(1)?>
    </tr>
    <tr>
      <th class='display-6' rowspan ="4">Demandes</th>
      <th scope="row">Moins d'un jour</th>
      <?php countAndInsertPerformance(2, 0, 1)?>
    </tr>
    <tr>
      <th scope="row">Entre 1 et 3 jours</th>
      <?php countAndInsertPerformance(2, 1, 3)?>
    </tr>
    <tr>
      <th scope="row">Plus de 3 jours</th>
      <?php countAndInsertPerformance(2, 3, 7)?>
    </tr>
    <tr>
      <th scope="row">En Backlog</th>
      <?php countAndInsertBacklog(2)?>
    </tr>
  </tbody>
</table>
</div>

<div id="table">
  <h1>Tendance</h1>
  <h3 class="text-right"><?php echo $this_week?></h3>
<table class="table table-striped table-dark">
  <thead>
  <tr>
    <th class="bg-info" scope="col">&nbsp;</th>  
    <th class="bg-info" colspan="5"><h5>Priorité par type</h5></th>
  </tr>
    <tr>
      <th scope="col">#</th> 
      <th scope="col">Très haute</th>
      <th scope="col">Haute</th>
      <th scope="col">Moyenne</th>
      <th scope="col">Basse</th>
      <th scope="col">Très basse</th>
    </tr>
  </thead>
</div>