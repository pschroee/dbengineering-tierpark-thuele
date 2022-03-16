<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Db;


$MitarbeiterRouter = function (RouteCollectorProxy $group) {

    // Get all
    $group->get("", function (Request $request, Response $response) {
        require_once __DIR__ . "/../schemas/Mitarbeiter.php";

        $sql = "SELECT * FROM Mitarbeiter";

        try {
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully fetched all Mitarbeiter",
                    "data" => $result,
                    "schema" => $MitarbeiterSchema
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

    // Create
    $group->post("", function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();

        $sql = "INSERT INTO Mitarbeiter (
                Personalnummer, Anrede, Name, Vorname, Straße, PLZ, Telefon, RollenId
            )
            VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?
            );";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["Personalnummer"]);
            $stmt->bindParam(2, $data["Anrede"]);
            $stmt->bindParam(3, $data["Name"]);
            $stmt->bindParam(4, $data["Vorname"]);
            $stmt->bindParam(5, $data["Straße"]);
            $stmt->bindParam(6, $data["PLZ"]);
            $stmt->bindParam(7, $data["Telefon"]);
            $stmt->bindParam(8, $data["RollenId"]);

            $result = $stmt->execute();

            $db = null;

            $Personalnummer = $data["Personalnummer"];
            if ($result) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully created Mitarbeiter with Personalnummer $Personalnummer",
                    "data" => $data
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

    // Update
    $group->put('/{Personalnummer}', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $Personalnummer = $request->getAttribute('Personalnummer');

        $sql = "UPDATE Mitarbeiter SET
                Personalnummer = ?,
                Anrede = ?,
                Name = ?,
                Vorname = ?,
                Straße = ?,
                PLZ = ?,
                Telefon = ?,
                RollenId = ?
            WHERE Personalnummer = $Personalnummer";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["Personalnummer"]);
            $stmt->bindParam(2, $data["Anrede"]);
            $stmt->bindParam(3, $data["Name"]);
            $stmt->bindParam(4, $data["Vorname"]);
            $stmt->bindParam(5, $data["Straße"]);
            $stmt->bindParam(6, $data["PLZ"]);
            $stmt->bindParam(7, $data["Telefon"]);
            $stmt->bindParam(8, $data["RollenId"]);

            $result = $stmt->execute();

            $db = null;
            $Personalnummer = $data["Personalnummer"];
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully updated Mitarbeiter with Personalnummer $Personalnummer",
                    "data" => $data
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

    // Delete
    $group->delete('/{Personalnummer}', function (Request $request, Response $response, array $args) {
        $Personalnummer = $request->getAttribute('Personalnummer');

        $sql = "DELETE FROM Mitarbeiter WHERE Personalnummer = $Personalnummer";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);

            $result = $stmt->execute();

            $db = null;
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully deleted Mitarbeiter with Personalnummer $Personalnummer",
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
