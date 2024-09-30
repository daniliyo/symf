git clone git@github.com:daniliyo/symf.git .

cd symf

cp .env.dist .env

cp app/.env.dev app/.env

After
```
docker-compose up
```

After booting the container, you can use composer and the symfony cli insight the php-apache container:
```
docker exec -it symfony-apache-php bash
composer install
```

Urls
http://localhost:8081/index.php/table/ - Generate data
http://localhost:8081/index.php/table/public - Finaly results