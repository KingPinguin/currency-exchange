# currency-exchange
	
## Setup
To run this project localy, use the commands bellow in that order:

```
$ cd currency-exchange
$ composer install
$ yarn install
```

Open .env file in root folder and modify your DATABASE_URL. 

```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
$ php bin/console app:refresh-rates
```
php bin/console app:refresh-rates is cron-ready task from the assignement, the first time it runs will update the db with the rates provided in the task and every next time rates will be updated from the api_layer api

```
$ yarn encore dev
$ php bin/console server:run 8000
```

Open a new console command tab and activate another server for api
```
$ php bin/console server:run 8001
```

### Visit http://localhost:8000/ in browser
In the assignement i accidentally read that user registration and authentication is required instead of not required so to access the app sign up first and then login with the email and password you provided.