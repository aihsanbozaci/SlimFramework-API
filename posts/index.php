<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';
$app = AppFactory::create();


//all posts
$app->get('/slim4/posts/', function (Request $request, Response $response, $args) {

    $db = new Db();

    try {
        $db = $db->connect();

        $posts = $db->query("SELECT * FROM posts")->fetchAll(PDO::FETCH_OBJ);

        if (!empty($posts)) {
            $response->getBody()->write(json_encode($posts, JSON_PRETTY_PRINT));
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