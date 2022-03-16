<?php

use App\Model\Db;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

// Require all Router
require_once __DIR__ . "/../routes/Mitarbeiter.php";
require_once __DIR__ . "/../routes/Fahrgeschäft.php";
require_once __DIR__ . "/../routes/Dashboard.php";
require_once __DIR__ . "/../routes/MitarbeiterFahrgeschäft.php";
require_once __DIR__ . "/../routes/Rollenberechtigung.php";

$app = AppFactory::create();

$app->setBasePath('/api/public');

$app->addBodyParsingMiddleware();

$app->addRoutingMiddleware();
// $app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);

$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
    $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

    $response = $handler->handle($request);

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', "GET, PUT, POST, DELETE");
    $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

    // Optional: Allow Ajax CORS requests with Authorization header
    // $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

    return $response;
});

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('Api is running!');
    return $response;
});

$app->group("/Mitarbeiter", $MitarbeiterRouter);
$app->group("/Fahrgeschäft", $FahrgeschäftRouter);
$app->group("/Dashboard", $DashboardRouter);
$app->group("/MitarbeiterFahrgeschäft", $MitarbeiterFahrgeschäftRouter);
$app->group("/Rollenberechtigung", $RollenberechtigungRouter);

$app->run();
