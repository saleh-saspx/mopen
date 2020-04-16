## 1 - Set DataBase Connection In .env
`DB_DATABASE=mopen`

<br>

`DB_USERNAME=root`

<br>

`DB_PASSWORD=`

## 2 -  Run Install Commend

`composer install`

<br>

`php artisan install:app`

<br>

`QUEUE_CONNECTION=database`

## 3 - Run QueueJob Commend
`php artisan queue:work`
