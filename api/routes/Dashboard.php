<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Db;

$DashboardRouter = function (RouteCollectorProxy $group) {
    $group->get("", function (Request $request, Response $response) {

        // Gehege, Tier, Mitarbeiter, Gastronomie, Themenwelt, Fahrgeschaeft, Veranstaltung
        $sql = "
            SELECT (
                SELECT COUNT(*) FROM Mitarbeiter
            ) AS Mitarbeiter,
            (
                SELECT COUNT(*) FROM Gehege
            ) AS Gehege,
            (
                SELECT COUNT(*) FROM Tier
            ) AS Tier,
            (
                SELECT COUNT(*) FROM Gastronomie
            ) AS Gastronomie,
            (
                SELECT COUNT(*) FROM Themenwelt
            ) AS Themenwelt,
            (
                SELECT COUNT(*) FROM Fahrgeschaeft
            ) AS Fahrgeschäft,
            (
                SELECT COUNT(*) FROM Veranstaltung
            ) AS Veranstaltung,
            (
                SELECT COUNT(*) FROM Gebaeude
            ) AS Gebäude
        ";

        try {
            $db = new Db;
            $conn = $db->connect();
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            if ($result) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully fetched Dashboard",
                    "data" => $result[0]
                )
            ));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    });
};
