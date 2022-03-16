<?php

use App\Model\Db;

$themenweltDropdownItems = array();

$sql = "SELECT Id AS value, Name AS label FROM Themenwelt";

try {
	$db = new Db;
	$conn = $db->connect();
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);

	if ($result) $themenweltDropdownItems = $result;
} catch (PDOException $e) {
	echo ($e->getMessage());
}


$FahrgeschäftSchema = array(
	array(
		"name" => "Id",
		"label" => "Id",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => false
		),
		"required" => true,
		"type" => "number",
	),
	array(
		"name" => "Name",
		"label" => "Name",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "text",

	),
	array(
		"name" => "Baujahr",
		"label" => "Baujahr",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "number",

	),
	array(
		"name" => "Beschreibung",
		"label" => "Beschreibung",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "textarea",

	),
	array(
		"name" => "letzteWartung",
		"label" => "Letzte Wartung",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "datetime",

	),
	array(
		"name" => "ThemenweltId",
		"label" => "Themenwelt",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "dropdown",
		"dropdownItems" => $themenweltDropdownItems,
	),
);
