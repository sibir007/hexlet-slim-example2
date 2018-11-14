<?php
namespace App;

require __DIR__ . '/../vendor/autoload.php';

use App\Repository;
use App\ValidatorInterface;
use Slim\Views\PhpRenderer;
use Stringy\create as s;

$repo = new \App\Repository();

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($configuration);

$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');

$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'a1.ph');
});

$app->get('/courses', function ($request, $response) use ($repo) {
    $params = [
        'courses' => $repo->all()
    ];
    return $this->renderer->render($response, 'courses/index.phtml', $params);
});

// BEGIN (write your solution here)
$app->post('/courses', function ($request, $response) use ($repo) {
    $validator = new Validator();
    $cours = $request->getParsedBodyParam('course');
    //var_dump($cours);
    $errors = $validator->validate($cours);
    //var_dump($errors);
    if (count($errors) === 0) {
        $repo->save($cours);
        return $response->withRedirect('/');
    }
    $params = [
        'cours' => $cours,
        'errors' => $errors
    ];
    return $this->renderer->render($response, 'courses/new.phtml', $params);
});
$app->get('/courses/new', function ($request, $response) {
    return $this->renderer->render($response, 'courses/new.phtml');
});
// END

$app->run();

