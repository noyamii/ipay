# Ipay
## How to run
For the first time.
```
docker-compose build app
```

then:
```
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate
```

for shutting down:
```
docker-compose down
```
