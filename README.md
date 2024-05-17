# QuizWiz
## A project for the REDBERRY internship

This is the backend of the QuizWiz application where users can browse and take quizzes.

#
### Table of Contents
* [Prerequisites](#prerequisites)
* [Tech Stack](#tech-stack)
* [Getting Started](#getting-started)
* [Migrations](#migration)
* [Development](#development)
* [Resources](#resources)

#
### Prerequisites

* *PHP@8.2 and up*
* *Composer@2.2 and up*
* *MySQL@8 and up*
* *MailHog*

#
### Tech Stack

* [Laravel@11.x](https://laravel.com/docs/11.x)
* [PHP CS Fixer](https://cs.symfony.com/)
* [Laravel Nova](https://nova.laravel.com/)

#
### Getting Started

1\. Clone the repository:
```sh
git clone git@github.com:RedberryInternship/quizwiz-back-tamar-sinauridze.git
```

2\. Install dependencies:
```sh
composer install
```

3\. Copy **.env** file:
```sh
cp .env.example .env
```

4\. Fill in the **.env** file:

>DB_CONNECTION=mysql

>DB_HOST=127.0.0.1

>DB_PORT=3306

>DB_DATABASE=*****

>DB_USERNAME=*****

>DB_PASSWORD=*****

5\. Generate the key:
```sh
php artisan key:generate
```

6\. Create the storage link:
```sh
php artisan storage:link
```

7\. If you are going to make changes in this project, you also need to install and configure PHP CS Fixer in your code editor using [this guide](https://redberry.gitbook.io/resources/laravel/php-is-linteri#id-4890).

#
### Migration

Migrate the database:
```sh
php artisan migrate --seed
```

#
### Development

Run the server:

```sh
php artisan serve
```
The server should be running on http://localhost:8000/

Start the mailHog:
```sh
~/go/bin/MailHog
```

#
### Resources
* [Figma](https://www.figma.com/file/QTWoxa2OYVayZ04WJ0ZZ9k/QuizWiz?type=design&node-id=1238-2202&mode=design&t=UX8IDJLhO3p8kb2k-0) - The design of the project
* [DrawSQL](https://drawsql.app/teams/sin165s-team/diagrams/redberry-quizwiz) - The database diagram
* [API Documentation](https://documenter.getpostman.com/view/23797534/2sA3JT1xKf) - The Postman documentation of the API
