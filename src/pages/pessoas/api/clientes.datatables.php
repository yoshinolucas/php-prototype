<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/includes/config.php";

$dbname = 'clientes';
// Reading value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();

// Search
$searchQuery = " ";
if($searchValue != ''){
   $searchQuery = " AND (email LIKE :email OR 
        name LIKE :name ) ";
   $searchArray = array( 
        'email'=>"%$searchValue%",
        'name'=>"%$searchValue%",       
   );
}

// Total number of records without filtering
$stmt = $dbh->prepare("SELECT COUNT(*) AS allcount FROM ".$dbname." ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $dbh->prepare("SELECT COUNT(*) AS allcount FROM ".$dbname." WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

// Fetch records
$stmt = $dbh->prepare("SELECT id, name, email, telefone, criado_em, atualizado_em FROM ".$dbname." WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach ($searchArray as $key=>$search) {
   $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$usersRecords = $stmt->fetchAll();

$data = array();

foreach ($usersRecords as $row) {
   $data[] = array(
      "DT_RowId"=>$row['id'],
      "select"=>"",
      "id"=>$row['id'],
      "name"=>$row['name'],
      "email"=>$row['email'],
      "telefone"=>$row['telefone'],
      "criado_em"=>$row['criado_em'],
      "atualizado_em"=>$row['atualizado_em']
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
