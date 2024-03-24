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
    } finally {
        $db = null;
    }

    return $response;
});

// adding comments from given example link
$app->post('/slim4/comments/add', function (Request $request, Response $response, $args) {
    $db = new Db();
    try {
        $db = $db->connect();

        $data = $request->getBody()->getContents();
        $comments = json_decode($data, true);

        if (!$comments) {
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

        foreach ($comments as $comment) {
            $postId = $comment["postId"] ?? null;
            $id = $comment['id'] ?? null;
            $name = $comment["name"] ?? null;
            $body = $comment["body"] ?? null;
            $email = $comment["email"] ?? null;

            if (!$postId || !$name || !$body || !$email || !$id) {
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

            $statement = $db->prepare('INSERT INTO comments (id, postId, email, name, body) VALUES (:id, :postId, :email, :name, :body)');

            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->bindParam(':postId', $postId, PDO::PARAM_INT);
            $statement->bindParam(':email', $email, PDO::PARAM_STR);
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
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
                    'text' => "Tüm yorumlar eklendi!"
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



$app->run();
