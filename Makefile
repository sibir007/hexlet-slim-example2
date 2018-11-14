start:
	php -S localhost:8000 -t public public/index.php

test:
	phpunit tests
