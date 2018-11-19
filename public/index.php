<?php
namespace App;
require __DIR__ . '/../vendor/autoload.php';

use App\Repository;
use App\ValidatorInterface;
use Slim\Views\PhpRenderer;
use Stringy\create as s;


$repo = new Repository();
//echo '<pre>';
//var_dump($_SESSION);
//echo '<pre>';
$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($configuration);

$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'index.phtml');
});

$app->get('/posts', function ($request, $response) use ($repo) {
    $flash = $this->flash->getMessages();

    $params = [
        'flash' => $flash,
        'posts' => $repo->all()
    ];
    return $this->renderer->render($response, 'posts/index.phtml', $params);
})->setName('posts');

// BEGIN (write your solution here)

$app->get('/posts/new', function ($request, $response){
	$param = [
		'post' => [
			'name' => '',
			'body' => '',
			],
		'errors' => []
	];
	return $this->renderer->render($response, 'posts/new.phtml', $param);
	}
);
$app->post('/posts', function ($request, $response) use ($repo) {
	echo "in post \n";
	$validator = new Validator();
	$post = $request->getParsedBodyParam('post');
	//var_dump($post);
	$errors = $validator->validate($post);
	//echo "errors \n";
	//echo '<pre>';
	//var_dump($post);
	//echo '<pre>';
	if (count($errors) === 0) {
		$repo->save($post);
		//echo "repo";
		//var_dump($repo);
		$this->flash->addMessage('Sucses', 'Post has been created');
		return $response->withRedirect('/posts');
		}
	$param = [
	'post' => $post,
	'errors' => $errors
	];
	return $this->renderer->render($response, 'posts/new.phtml', $param);
	}
);
$app->delete('/post/{id}', function ($request, $response, $args) use ($repo) {
	$id = $args['id'];
	$repo->destroy($id);
	$this->flash->addMessage('success', 'Post has been deleted') ;
	return $response->withRedirect('/posts');
	}
);

$app->get('/post/{id}/edit', function ($request, $response, $args) use ($repo) {
	$id = $args['id'];
	$post = $repo->find($id);
	$param = [
		'post' => $post,
		'errors' => [],
		'id' => $id
		];
	return $this->renderer->render($response, 'posts/edit.phtml', $param);
	}
);


$app->patch('/post/{id}', function ($request, $response, $args) use ($repo) {
	$id = $args['id'];
	$post = $repo->find($id);
	$data = $request->getParsedBodyParam('post');
	$post['name'] = $data['name'];
	$post['body'] = $data['body'];
	$validator = new Validator();
	$errors = $validator->validate($post);
	if (count($errors === 0)) {
		$this->flash->addMessage('success', 'Post has been updated');
		$repo->save($post);
		return $response->withRedirect('/posts');
	}
	}
);

//END

$app->run();

