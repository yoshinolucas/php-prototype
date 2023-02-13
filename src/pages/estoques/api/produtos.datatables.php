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

$table = 'produtos';
$cols = ['id','name','marca','unidades','atualizado_em','active','criado_em'];

// Search
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (name LIKE :name OR 
   marca LIKE :marca ) ";
   $searchArray = array( 
        'name'=>"%$searchValue%",  
        'marca'=>"%$searchValue%"    
   );
} 

if($_POST['min'] != '' AND $_POST['max'] != '') $searchQuery .= "AND criado_em ".$_POST['min']." between ".$_POST['max']." ";
if($_POST['marca'] != 'todos') $searchQuery .= "AND marca = '".$_POST['marca']."' ";

$records = mysql_fetchCount("SELECT COUNT(*) AS allcount FROM ".$table." ");
$totalRecords = $records['allcount'];

$records = mysql_fetchCount("SELECT COUNT(*) AS allcount FROM ".$table." WHERE 1 ".$searchQuery, $searchArray);
$totalRecordwithFilter = $records['allcount'];

$values = [];
foreach ($searchArray as $key=>$search) {
   $keyFormat = ':'. $key;
   $values[$keyFormat] = $search;
}
$values[':limit'] = (int)$row;
$values[':offset'] = (int)$rowperpage;

$records = mysql_fetchAll("SELECT ".implode(',',$cols)." FROM ".$table." WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset", $values);

$data = Array();
foreach ($records as $row) {
   $atualizado_em = '';
   $criado_em = date_format(date_create($row['criado_em']),'d/m/Y H:i:s');
   if (!empty($row['atualizado_em'])) $atualizado_em = date_format(date_create($row['atualizado_em']),'d/m/Y H:i:s');
   $data[] = array(
      "DT_RowId"=>$row['id'],
      "select"=>"",
      "active"=>$row['active'] == 1 ? "<i class='fa fa-check'></i>" : "",
      "id"=>$row['id'],
      "name"=>$row['name'],
      "marca"=>$row['marca'],
      "unidades"=>$row['unidades'],
      "atualizado_em"=>$atualizado_em,
      "criado_em"=>$criado_em,
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