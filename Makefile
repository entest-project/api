config/jwt/private.pem:
	mkdir -p config/jwt
	openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096 -pass "pass:${JWT_PASSPHRASE}"

config/jwt/public.pem: config/jwt/private.pem
	openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout -passin "pass:${JWT_PASSPHRASE}"

deploy: config/jwt/public.pem
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php composer-setup.php
	php -r "unlink('composer-setup.php');"
	./composer.phar install
	./bin/console doctrine:migrations:migrate --no-interaction
	chmod -R 777 var/cache/

.PHONY: deploy
