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
    } finally {
        $db = null;
    }
    return $response;
});


// adding posts from given example link
$app->post('/slim4/posts/add', function (Request $request, Response $response, $args) {
    $db = new Db();
    try {
        $db = $db->connect();

        $data = $request->getBody()->getContents();
        $posts = json_decode($data, true);

        if (!$posts) {
            $response->getBody()->write(json_encode(
                array(
                    "error" => array(
                        'text' => "Eksik veya hatalı veri"
                    )
                ),
                JSON_PRETTY_PRINT
            ));
            return $response->withHeader("Content-Type", 'application/json')->withStatus(400);
        }

        foreach ($posts as $post) {
            $userId = $post["userId"] ?? null;
            $title = $post["title"] ?? null;
            $body = $post["body"] ?? null;

            if (!$userId || !$title || !$body) {
                $response->getBody()->write(json_encode(
                    array(
                        "error" => array(
                            'text' => "Eksik parametre"
                        )
                    ),
                    JSON_PRETTY_PRINT
                ));
                return $response->withHeader("Content-Type", 'application/json')->withStatus(400);
            }

            $statement = $db->prepare('INSERT INTO posts (userId, title, body) VALUES (:userId, :title, :body)');

            $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
            $statement->bindParam(':title', $title, PDO::PARAM_STR);
            $statement->bindParam(':body', $body, PDO::PARAM_STR);

            $result = $statement->execute();

            if (!$result) {
                $response->getBody()->write(json_encode(
                    array(
                        "error" => array(
                            'text' => "Ekleme sırasında hata oluştu"
                        )
                    ),
                    JSON_PRETTY_PRINT
                ));
                return $response->withHeader("Content-Type", 'application/json')->withStatus(500);
            }
        }

        $response->getBody()->write(json_encode(
            array(
                "success" => array(
                    'text' => "Tüm postlar eklendi!"
                )
            ),
            JSON_PRETTY_PRINT
        ));
        return $response->withHeader("Content-Type", 'application/json')->withStatus(200);
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
    } finally {
        $db = null;
    }

    return $response;
});


//a single post and comments
$app->get('/slim4/posts/{id}/comments', function (Request $request, Response $response, $args) {

    $db = new Db();
    $db = $db->connect();

    $id = $args['id'];

    try {
        // Comments for the post given in the url
        $comments = $db->query("SELECT * FROM comments WHERE postId = $id")->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($comments, JSON_PRETTY_PRINT));
        return $response
            ->withHeader("Content-Type", 'application/json')
            ->withStatus(200);
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
    } finally {
        $db = null;
    }

    return $response;
});

$app->run();
