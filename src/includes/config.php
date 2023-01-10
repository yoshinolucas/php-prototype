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




// JWT

function base64url_encode($data)
{
	// First of all you should encode $data to Base64 string
	$b64 = base64_encode($data);

	// Make sure you get a valid result, otherwise, return FALSE, as the base64_encode() function do
	if ($b64 === false) {
		return false;
	}

	// Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
	$url = strtr($b64, '+/', '-_');

	// Remove padding character from the end of line and return the Base64URL result
	return rtrim($url, '=');
}
function base64url_decode($data, $strict = false)
{
	// Convert Base64URL to Base64 by replacing “-” with “+” and “_” with “/”
	$b64 = strtr($data, '-_', '+/');

	// Decode Base64 string and return the original data
	return base64_decode($b64, $strict);
}

function validajwt($jwt){
	global $JWT_KEY;
	$part = explode(".",$jwt);
	$header = $part[0];
	$payload = $part[1];
	$signature = $part[2];

	
	$valid = hash_hmac('sha256',"$header.$payload",$JWT_KEY,true);
	$valid = base64url_encode($valid);
	
	if($signature == $valid){
		return true;
	}else{
		return false;
	}
}

function atualizajwt(){
	global $JWT_KEY;
	$jwt = $_COOKIE['token'];
	$part = explode(".",$jwt);
	$header = $part[0];
	$payload = $part[1];
	$signature = $part[2];
	
	$payload = json_decode(base64url_decode($payload),true);
	
	$payload['generated'] = time();
	$payload['valid'] = time() + (4 * 60 * 60);
	
	$payload = json_encode($payload);
	$payload = base64url_encode($payload);
	
	$signature = hash_hmac('sha256',"$header.$payload",$JWT_KEY,true);
	$signature = base64url_encode($signature);
	
	geracookie("token",$header.".".$payload.".".$signature);
}

function geracookie($name, $data, $expire = false){
	global $HTTP_HOST, $HTTP_SSL;
	$domain = $HTTP_HOST ? $HTTP_HOST : false;
	if ($HTTP_SSL == 1){
		$ssl = true;
	} else {
		$ssl = false;
	}

	$expiration = ($expire ? (time() - 3600) : (time()+60*60*4));
	setcookie($name, $data, $expiration, "/", $domain, $ssl, true);
}

function getpayload($jwt){
	$part = explode(".",$jwt);
	$payload = $part[1];
	
	return json_decode(base64url_decode($payload));
}

function gerajwt($options = Array()){
	global $OWNER, $JWT_KEY;
	$header = [
	'alg' => 'HS256',
	'typ' => 'JWT'
	];
	
	$header = json_encode($header);
	$header = base64_encode($header);
	
	$payload = [
	'owner' => $OWNER,
	'client' => $_SERVER['REMOTE_ADDR'],
	'generated' => time(),
	'valid' => time() + (4 * 60 * 60),
	'options' => $options
	];
	$payload = json_encode($payload);
	$payload = base64url_encode($payload);
	
	$signature = hash_hmac('sha256',"$header.$payload",$JWT_KEY,true);
	$signature = base64url_encode($signature);
	
	return $header.".".$payload.".".$signature;
}
function protege(){
	global $OWNER;
	$jwt = $_COOKIE['token'];
	if (!validajwt($jwt)){
		print "<script>location.href='/index.php?msg=2';</script>";
		exit;
	}
	$payload = getpayload($jwt);
	
	if ($payload->owner != $OWNER){
		print "<script>location.href='/index.php?msg=3';</script>";
		exit;
	}
	if ($payload->generated > $payload->valid){
		print "<script>location.href='/index.php?msg=4';</script>";
		exit;
	}
	if ($payload->valid < time()){
		print "<script>location.href='/index.php?msg=5';</script>";
		exit;
	}
	
	atualizajwt();
	
}
?>