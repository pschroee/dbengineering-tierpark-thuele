<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Db;

$FahrgeschäftRouter = function (RouteCollectorProxy $group) {

    // Get all
    $group->get("", function (Request $request, Response $response) {
        require_once __DIR__ . "/../schemas/Fahrgeschäft.php";

        $sql = "SELECT * FROM Fahrgeschaeft";

        try {
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            if ($result) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully fetched all Fahrgeschäfte",
                    "data" => $result,
                    "schema" => $FahrgeschäftSchema,
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

        $sql = "INSERT INTO Fahrgeschaeft (
                Baujahr, letzteWartung, Name, Beschreibung, ThemenweltId
            )
            VALUES (
                ?, ?, ?, ?, ?
            );";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["Baujahr"]);
            $stmt->bindParam(2, $data["letzteWartung"]);
            $stmt->bindParam(3, $data["Name"]);
            $stmt->bindParam(4, $data["Beschreibung"]);
            $stmt->bindParam(5, $data["ThemenweltId"]);

            $result = $stmt->execute();
            $id = $conn->lastInsertId();
            $data["Id"] = $id;
            $db = null;

            $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully created Fahrgeschäft with Id $id",
                    "data" => $data,
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

    //Update
    $group->put('/{Id}', function (Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();
        $Id = $request->getAttribute('Id');

        $sql = "UPDATE Fahrgeschaeft SET
                Id = ?,
                Baujahr = ?,
                letzteWartung = ?,
                Name = ?,
                Beschreibung = ?,
                ThemenweltId = ?
            WHERE Id = $Id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["Id"]);
            $stmt->bindParam(2, $data["Baujahr"]);
            $stmt->bindParam(3, $data["letzteWartung"]);
            $stmt->bindParam(4, $data["Name"]);
            $stmt->bindParam(5, $data["Beschreibung"]);
            $stmt->bindParam(6, $data["ThemenweltId"]);

            $result = $stmt->execute();

            $db = null;
            $id = $data["Id"];
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully updated Fahrgeschäft with Id $id",
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

    //Delete
    $group->delete('/{Id}', function (Request $request, Response $response, array $args) {
        $Id = $request->getAttribute('Id');

        $sql = "DELETE FROM Fahrgeschaeft WHERE Id = $Id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);

            $result = $stmt->execute();

            $db = null;
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully deleted Fahrgeschäft with Id $Id",
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
