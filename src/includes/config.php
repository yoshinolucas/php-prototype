<?php
// Variáveis do mysql
$host = getenv("DB_HOST");
$dbname = getenv("DB_NAME");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");

// Locale
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_ALL, NULL);
setlocale(LC_ALL, 'pt_BR.utf8');

// Conexão MySQL

$dsn = "mysql:dbname=".$dbname.";host=".$host;

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

function mysql_fetchAll($query, $values = Array(), $fetchtype = PDO::FETCH_ASSOC){
    global $dbh;
    $stmt = $dbh->prepare($query);
    foreach($values as $k=>$v){
        if($k == ':limit' or $k == ':offset'){
            $stmt->bindValue($k, $v, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($k, $v); 
        }         
    }
    $run = $stmt->execute();
	if ($run == false){
		error_log(serialize($stmt->errorInfo()));
		error_log(serialize($values));
		error_log($query);
	}
	$result = $stmt->fetchAll($fetchtype);
	return $result;
}

function mysql_numRows($query, $values = Array()){
    global $dbh;
    $stmt = $dbh->prepare($query);
    foreach($values as $k=>$v){
        $stmt->bindValue($k, $v);
    }
    $run = $stmt->execute();
    if(!$run){
        error_log(serialize($stmt->errorInfo()));
        error_log(serialize($values));
        error_log($query);
    }
    $count = $stmt->rowCount();
    return $count;
}

function mysql_fetchRow($query, $values = Array()){
	global $dbh;
	$stmt = $dbh->prepare($query);
	foreach ($values as $k => $v){
		$stmt->bindValue($k, $v);
	}
	$run = $stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result;
}

function mysql_fetch($query, $values = Array()){
    global $dbh;
    $stmt = $dbh->prepare($query);
    foreach($values as $k=>$v){
        $stmt->bindValue($k, $v);
    }
    $run = $stmt->execute();
    if(!$run){
        error_log(serialize($stmt->errorInfo()));
        error_log(serialize($values));
        error_log($query);
    }
    return $run;
}

function mysql_fetchId($query, $values = Array()){
	global $dbh;
	$stmt = $dbh->prepare($query);
	foreach ($values as $k => $v){
		$stmt->bindValue($k, $v);
	}
	$run = $stmt->execute();
	
	if ($run == false){
		error_log(serialize($stmt->errorInfo()));
		error_log(serialize($values));
		error_log($query);
	}
	
	return $dbh->lastInsertId(); 
}
?>