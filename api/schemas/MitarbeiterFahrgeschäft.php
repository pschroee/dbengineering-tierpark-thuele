<?php

use App\Model\Db;

$mitarbeiterDropdownItems = array();
$sql = "
SELECT
	Personalnummer AS value,
	CONCAT(Vorname, ' ',Name,' [',Personalnummer,']') AS label
FROM Mitarbeiter
JOIN
	Rollenberechtigung ON Mitarbeiter.RollenId = Rollenberechtigung.RollenId
WHERE
	Rollenberechtigung.Berechtigung = 'FAHRGESCHÄFT';
";

try {
	$db = new Db;
	$conn = $db->connect();
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);
	if ($result) $mitarbeiterDropdownItems = $result;
} catch (PDOException $e) {
	echo ($e->getMessage());
}

$fahrgeschäftDropdownItems = array();
$sql = "SELECT Id AS value, Name AS label FROM Fahrgeschaeft;";

try {
	$stmt = $conn->query($sql);
	$result = $stmt->fetchAll(PDO::FETCH_OBJ);

	if ($result) $fahrgeschäftDropdownItems = $result;
} catch (PDOException $e) {
	echo ($e->getMessage());
}


$MitarbeiterFahrgeschäftSchema = array(
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
		"name" => "FahrgeschaeftsId",
		"label" => "Fahrgeschäft",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "dropdown",
		"dropdownItems" => $fahrgeschäftDropdownItems,
	),
	array(
		"name" => "Personalnummer",
		"label" => "Mitarbeiter",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "dropdown",
		"dropdownItems" => $mitarbeiterDropdownItems,
	),
	array(
		"name" => "Arbeitsanfang",
		"label" => "Arbeitsanfang",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "datetime",

	),
	array(
		"name" => "Arbeitsende",
		"label" => "Arbeitsende",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => true,
		"type" => "datetime",

	),
	array(
		"name" => "Arbeitszeit",
		"label" => "Arbeitszeit",
		"options" => array(
			"filter" => true,
			"sort" => true,
			"display" => true
		),
		"required" => false,
		"type" => "timestampdiff",
		"hideInModal" => true,
	),
);
