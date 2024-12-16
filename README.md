# laravel-mongodb

- configure Mongod with laravel
	- Connect with php
	- search php.net mongodb
	- install
	- for windows
	- GO to wamp : bin/php/version/ and check which ts or nts used : you can see a file named (php8ts.dll in my case)
	- according to that download dll they are providing i need to download :(php_mongodb-1.19.3-8.1-ts-x64.zip)(8.1 php)
	- copy dll file from zip 
	- goto ext folder in used php version in wamp/bin/php/ 
	- pest file there.
	- open php.ini ->
	- under Dynamic Extensions section ->
	- add a line : extension=php_mongodb.dll
