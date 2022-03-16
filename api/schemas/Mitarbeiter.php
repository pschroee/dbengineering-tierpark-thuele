<?php

use App\Model\Db;

$rolleDropdownItems = array();

$sql = "SELECT Id AS value, Name AS label FROM Rolle";

try {
	$db = new Db;
	$conn = $db->connect();
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);

	if ($result) $rolleDropdownItems = $result;
} catch (PDOException $e) {
	echo ($e->getMessage());
}


$MitarbeiterSchema = array(
	array(
		"name" => "Personalnummer",
		"label" => "Personalnummer",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "number",
	),
	array(
		"name" => "Anrede",
		"label" => "Anrede",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "dropdown",
		"dropdownItems" => array(
			array(
				"value" => "Herr",
				"label" => "Herr",
			),
			array(
				"value" => "Frau",
				"label" => "Frau",
			),
		),

	),
	array(
		"name" => "Vorname",
		"label" => "Vorname",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "text",

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
		"name" => "Straße",
		"label" => "Straße",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "text",

	),
	array(
		"name" => "PLZ",
		"label" => "PLZ",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "number",
	),
	array(
		"name" => "Telefon",
		"label" => "Telefon",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "text",

	),
);
