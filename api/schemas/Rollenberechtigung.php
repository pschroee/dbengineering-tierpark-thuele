<?php

use App\Model\Db;

$rolleDropdownItems = array();
$sql = "SELECT Id AS value, Name AS label FROM Rolle;";
try {
	$db = new Db;
	$conn = $db->connect();
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);

	if ($result) $rolleDropdownItems = $result;
} catch (PDOException $e) {
	echo ($e->getMessage());
}


$rollenberechtigungSchema = array(
	array(
		"name" => "Id",
		"label" => "Id",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => false
		),
		"required" => false,
		"type" => "number",
	),
	array(
		"name" => "RollenId",
		"label" => "Rolle",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "dropdown",
		"dropdownItems" => $rolleDropdownItems,
	),
	array(
		"name" => "Berechtigung",
		"label" => "Berechtigung",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "dropdown",
		"dropdownItems" => array(
			array(
				"label" => "Zuweisung zu einer Gastronomie",
				"value" => "GASTRONOMIE",
			),
			array(
				"label" => "Zuweisung zu einem Gebäude",
				"value" => "GEBÄUDE",
			),
			array(
				"label" => "Zuweisung zu einem Fahrgeschäft",
				"value" => "FAHRGESCHÄFT",
			),
			array(
				"label" => "Zuweisung zu einem Gehege",
				"value" => "GEHEGE",
			),
			array(
				"label" => "Zuweisung zu einer Veranstaltung",
				"value" => "VERANSTALTUNG",
			),
		),
	),
);
