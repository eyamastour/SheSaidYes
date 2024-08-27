<?php
$host = "localhost";
$username ="root";
$password = "";

try{
    $conn = new PDO("mysql:host=$host;dbname=pidev", $username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e)
{
    echo "Connection Failed : " .$e->getMessage();
}

$response = array('success' => false);

if(isset($_POST['idpack']) && $_POST['idpack']!='' && isset($_POST['iduser']) && $_POST['iduser']!='' && isset($_POST['date']) && $_POST['date']!='' && isset($_POST['prixrespack']) && $_POST['prixrespack']!='')
{
	$sql = "INSERT INTO reservationpack(idpack, iduser, date,prixrespack) VALUES('".addslashes($_POST['idpack'])."', '".addslashes($_POST['iduser'])."','".addslashes($_POST['date'])."', '".addslashes($_POST['prixrespack'])."')";
	
	if($conn->query($sql))
	{
		$response['success'] = true;
	}
}

echo json_encode($response);