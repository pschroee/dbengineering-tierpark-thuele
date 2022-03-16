<?php

use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Model\Db;

$RollenberechtigungRouter = function (RouteCollectorProxy $group) {

    // Get all
    $group->get("", function (Request $request, Response $response) {
        require_once __DIR__ . "/../schemas/Rollenberechtigung.php";

        $sql = "SELECT * FROM Rollenberechtigung";

        try {
            $stmt = $conn->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully fetched all Rollenberechtigung",
                    "data" => $result,
                    "schema" => $rollenberechtigungSchema,
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

        $sql = "INSERT INTO Rollenberechtigung (
                RollenId, Berechtigung
            )
            VALUES (
                ?, ?
            );";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["RollenId"]);
            $stmt->bindParam(2, $data["Berechtigung"]);

            $result = $stmt->execute();
            $id = $conn->lastInsertId();
            $data["Id"] = $id;
            $db = null;



            if ($result) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully created Rollenberechtigung with Id $id",
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

        $sql = "UPDATE Rollenberechtigung SET
                Id = ?,
                RollenId = ?,
                Berechtigung = ?
            WHERE Id = $Id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $data["Id"]);
            $stmt->bindParam(2, $data["RollenId"]);
            $stmt->bindParam(3, $data["Berechtigung"]);

            $result = $stmt->execute();

            $db = null;
            $id = $data["Id"];
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully updated Rollenberechtigung with Id $id",
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

        $sql = "DELETE FROM Rollenberechtigung WHERE Id = $Id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);

            $result = $stmt->execute();

            $db = null;
            if ($stmt->rowCount() > 0) $response->getBody()->write(json_encode(
                array(
                    "message" => "Successfully deleted Rollenberechtigung with Id $Id",
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
