<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value']; 

$searchArray = array();

$cols = ['id','name','marca','unidades','atualizado_em','active','criado_em'];

// Search
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery .= "AND (name LIKE :name OR 
   marca LIKE :marca ) ";
   $searchArray = array( 
        'name'=>"%$searchValue%",  
        'marca'=>"%$searchValue%"    
   );
} 

if($_POST['min'] != '' AND $_POST['max'] != '') $searchQuery .= "AND criado_em ".$_POST['min']." between ".$_POST['max']." ";
if($_POST['marca'] != 'todos') $searchQuery .= "AND marca = '".$_POST['marca']."' ";

$records = mysql_fetchRow('SELECT COUNT(*) AS allcount FROM produtos');
$totalRecords = $records['allcount'];

$records = mysql_fetchRow("SELECT COUNT(*) AS allcount FROM produtos WHERE 1 ".$searchQuery);
$totalRecordwithFilter = $records['allcount'];

$values = [];
foreach ($searchArray as $key=>$search) {
   $values[':'.$key] = $search;
}
$values[':limit'] = (int)$row;
$values[':offset'] = (int)$rowperpage;

$usersRecords = mysql_fetchAll("SELECT ".implode(',',$cols)." FROM produtos WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset", $values);

foreach ($usersRecords as $row) {
   $data[] = array(
      "DT_RowId"=>$row['id'],
      "select"=>"",
      "active"=>$row['active'] == 1 ? "<i class='fa fa-check'></i>" : "",
      "id"=>$row['id'],
      "name"=>$row['name'],
      "marca"=>$row['marca'],
      "unidades"=>$row['unidades'],
      "atualizado_em"=>$row['atualizado_em'],
      "criado_em"=>$row['criado_em'],
   );
}

// Response
$response = array(
   "draw" => intval($draw),
   "recordsTotal" => $totalRecords,
   "recordsFiltered" => $totalRecordwithFilter,
   "data" => $data
);

echo json_encode($response);