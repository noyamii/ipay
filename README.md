# Ipay
### How to run
change ".env.example" to ".env"
For the first time.
```
docker-compose build app
```

Then:
```
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan migrate
```

Now you can use the app.


For shutting down:
```
docker-compose down
```
