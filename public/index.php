<?php
namespace App;
require __DIR__ . '/../vendor/autoload.php';

//session_start();
$repo = new Repository();

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];

$app = new \Slim\App($configuration);

$container = $app->getContainer();
$container['renderer'] = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');

$app->get('/', function ($request, $response) {
    $cart = json_decode($request->getCookieParam('cart', json_encode([])), true);
    $params = [
			'cart' => $cart,
			//'phpsessid' => $PHPSESSID
    ];
    return $this->renderer->render($response, 'index.phtml', $params);
});

// BEGIN (write your solution here)

$app->post('/cart-items', function ($request, $response) {
	$item = $request->getParsedBodyParam('item');
	$itemId = $item['id'];
	$itemName = $item['name'];
	$cart = json_decode($request->getCookieParam('cart', json_encode([])), true);
	if (collect($cart)->has($itemId)) {
		$cart[$itemId]['count'] = 1 + $cart[$itemId]['count'] ;	
	} else {
		$cart[$itemId]  = ['name' => $itemName, 'count' => 1];
	};
	$jsonEncodedCart = json_encode($cart);
	return $response->withHeader('Set-Cookie', "cart={$jsonEncodedCart}")->withRedirect('/');
	}
);
$app->delete('/cart-items', function ($request, $response) {
	$jsonEncodedCart = json_encode([]);
	return $response->withHeader('Set-Cookie', "cart={$jsonEncodedCart}")->withRedirect('/');

});
// END

$app->run();

