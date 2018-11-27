<?php

namespace App;

require '/../../vendor/composer/vendor/autoload.php';

use function Stringy\create as s;

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

session_start();

$app = new \Slim\App($configuration);

$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$users = [
    ['name' => 'admin', 'passwordDigest' => hash('sha256', 'secret')],
    ['name' => 'mike', 'passwordDigest' => hash('sha256', 'superpass')],
    ['name' => 'kate', 'passwordDigest' => hash('sha256', 'strongpass')]
];
// $users = [
//     ['name' => 'admin', 'passwordDigest' => 'secret'],
//     ['name' => 'mike', 'passwordDigest' => 'superpass'],
//     ['name' => 'kate', 'passwordDigest' => 'strongpass']
// ];

// BEGIN (write your solution here)
$app->get('/', function($req, $res) {
    $flash = $this->flash->getMessages(); 
    $params = ['flash' => $flash]; 
    return $this->renderer->render($res, 'index.phtml', $params);
});
$app->post('/session', function($req, $res) use ($users) {
    $user = $req->getParsedBodyParam('user');
    $userName = $user['name'];
    $userPassword = $user['password'];
    $userChesk = collect($users)->contains(function ($value, $key) use($userName, $userPassword) {
        return $value['name'] === $userName && $value['passwordDigest'] === hash('sha256', $userPassword);
    });
    if ($userChesk) {
        $_SESSION['userName'] = $userName;
        return $res->withRedirect('/');
    } else {
        $this->flash->addMessage('error', 'Wrong password or name');
        return $res->withRedirect('/');
    }
//$this->flash->addMessahe('success', 'Course Added')
});
$app->delete('/session', function ($req, $res) {
    session_unset();
    session_destroy();

    return $res->withRedirect('/');
});
// END

$app->run();

