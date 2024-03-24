<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';
$app = AppFactory::create();


//all comments
$app->get('/slim4/comments/', function (Request $request, Response $response, $args) {

    $db = new Db();

    try {
        $db = $db->connect();

        $comments = $db->query("SELECT * FROM comments")->fetchAll(PDO::FETCH_OBJ);

        if (!empty($comments)) {
            $response->getBody()->write(json_encode($comments, JSON_PRETTY_PRINT));
            return $response
                ->withHeader("Content-Type", 'application/json')
                ->withStatus(200);
        }

    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(
            array(
                "error" => array(
                    "text" => $e->getMessage(),
                    "code" => $e->getCode()
                )
            ),
            JSON_PRETTY_PRINT
        ));
        return $response->withHeader("Content-Type", 'application/json')->withStatus(500);
    }

    return $response;
});

$app->run();