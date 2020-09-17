# book-manager API


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- php >=7.1.3
- php-mbstring
- php-pdo_sqlite
- curl or similar tool like postman to make http request from your host 
- composer

### Installing

A step by step series of examples that tell you how to get a development env running

clone repository and run:
```
composer install
```
run local web server:
```
bin/symfony serve
```
you should see then your local server listening on localhost
```
http://127.0.0.1:8000
```

prepare database for dev environment 
```
bin/console doctrine:database:create --env=dev
bin/console doctrine:schema:update --force
```
prepare database for dev environment
```
bin/console doctrine:database:create --env=test
bin/console doctrine:schema:update --force --env=test
bin/console doctrine:fixtures:load --env=test -n
```

Now you are ready to consume api

## REST API USING
##### Create book: 
POST: 
`
/api/v1/books/create
`

e.g.
`http://127.0.0.1:8000/api/v1/books/create
`

with body:
````
{
    "name" : "Solaris - Stanisław Lem",
    "categories" : [
        { 
            "name" : "science fiction" 
        }
    ]
}
````
##### Get books: 
GET: 
`
/api/v1/books/get
`

e.g.
`http://127.0.0.1:8000/api/v1/books/get
`

##### Update book
PUT: 
`
/api/v1/books/update
`

e.g.
`http://127.0.0.1:8000/api/v1/books/update
`

with body:
````
{
    "id" : 1,
    "name" : "Solaris - Stanisław Lem",
    "categories" : [
        { 
            "name" : "science fiction" 
        }
    ]
}
````

##### Delete book
DELETE: 
`
/api/v1/books/delete/{id}
`

e.g.
`http://127.0.0.1:8000/api/v1/books/delete/1
`

## Testing
Create file env.local.test and put inside
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/book_manager_test.db"
```
run 
```
bin/phpunit
```
## Deployment
## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Tomasz Biedrzycki** - https://github.com/tbatinteriaeu



