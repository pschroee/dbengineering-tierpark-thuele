<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Db;

$MitarbeiterFahrgeschäftRouter = function (RouteCollectorProxy $group) {

    // Get all
    $group->get("", function (Request $request, Response $response) {
        require_once __DIR__ . "/../schemas/MitarbeiterFahrgeschäft.php";

        $sql = "SELECT * FROM Mitarbeiter_Fahrgeschaeft";

        try {
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully fetched all MitarbeiterFahrgeschäfte",
                    "data" => $result,
                    "schema" => $MitarbeiterFahrgeschäftSchema,
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

        $sql = "INSERT INTO Mitarbeiter_Fahrgeschaeft (
                FahrgeschaeftsId, Personalnummer, Arbeitsanfang, Arbeitsende
            )
            VALUES (
                ?, ?, ?, ?
            );";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["FahrgeschaeftsId"]);
            $stmt->bindParam(2, $data["Personalnummer"]);
            $stmt->bindParam(3, $data["Arbeitsanfang"]);
            $stmt->bindParam(4, $data["Arbeitsende"]);

            $result = $stmt->execute();
            $id = $conn->lastInsertId();
            $data["Id"] = $id;
            $db = null;



            if ($result) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully created MitarbeiterFahrgeschäft with Id $id",
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

        $sql = "UPDATE Mitarbeiter_Fahrgeschaeft SET
                Id = ?,
                FahrgeschaeftsId = ?,
                Personalnummer = ?,
                Arbeitsanfang = ?,
                Arbeitsende = ?
            WHERE Id = $Id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["Id"]);
            $stmt->bindParam(2, $data["FahrgeschaeftsId"]);
            $stmt->bindParam(3, $data["Personalnummer"]);
            $stmt->bindParam(4, $data["Arbeitsanfang"]);
            $stmt->bindParam(5, $data["Arbeitsende"]);

            $result = $stmt->execute();



            $db = null;
            $id = $data["Id"];

            $sql = "SELECT * FROM Mitarbeiter_Fahrgeschaeft WHERE Id = $id";
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully updated MitarbeiterFahrgeschäft with Id $id",
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

    //Delete
    $group->delete('/{Id}', function (Request $request, Response $response, array $args) {
        $Id = $request->getAttribute('Id');

        $sql = "DELETE FROM Mitarbeiter_Fahrgeschaeft WHERE Id = $Id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);

            $result = $stmt->execute();

            $db = null;
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully deleted MitarbeiterFahrgeschäft with Id $Id",
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
