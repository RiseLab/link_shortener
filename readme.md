# Link shortener API

RESTful API для сокращателя ссылок. Сокращатель ссылок - сервис, который позволяет пользователю создавать более короткие адреса, которые лучше передавать другим пользователям и собирает статистику по совершенным переходам.

## Getting started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- Web Server (Apache, Nginx, etc.)
- PHP v7.1.3
- MySQL v5.x
- Git
- Composer

### Installing

**This project based on Laravel Framework v5.7.6**

1. Download project sources from GitHub or clone it using Git
```
git clone git@github.com:RiseLab/link_shortener.git
```
2. Download project dependencies via Composer
```
composer update
```
3. Rename ```.env.example``` file to ```.env```, open and change database connection settings
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[your database name]
DB_USERNAME=[your database user name]
DB_PASSWORD=[your database user password]
```
4. Run database migrations
```
php artisan:migrate
```

## API description

### Authorization

Используется HTTP Basic Authorization, где параметром является пара логин-пароль в виде base64.

### Resources

1. **/api/v1/users** - ресурс, предоставляющий работу с пользователями:

   - [x] ```POST /api/v1/users``` - регистрация пользователя (авторизация не требуется);
   
   - [x] ```GET /api/v1/users/me``` - получение информации о текущем авторизованном пользователе;

2. **/api/v1/users/me/shorten_urls** - ресурс, предоставляющий работу с короткими ссылками пользователя:

   - [x] ```POST /api/v1/users/me/shorten_urls``` - создание новой короткой ссылки;
   
   - [x] ```GET /api/v1/users/me/shorten_urls``` - получение всех созданных коротких ссылок пользователя;
   
   - [x] ```GET /api/v1/users/me/shorten_urls/{id}``` - получение информации о конкретной короткой ссылке пользователя (также включает количество переходов);
   
   - [x] ```DELETE /api/v1/users/me/shorten_urls/{id}``` - удаление короткой ссылки пользователя;

   - [ ] **Отчеты**:
   
     - [ ] ```GET /api/v1/users/me/shorten_urls/{id}/[days,hours,min]?from_date=0000-00-00&amp;to_date=0000-00-00``` - получение временного графика количества переходов с группировкой по дням, часам, минутам;
     
     - [x] ```GET /api/v1/users/me/shorten_urls/{id}/referers``` - получение топа из 20 сайтов иcточников переходов;
     
3. **/api/v1/shorten_urls** ресурс, предоставляющий работу с короткими ссылками (авторизация не требуется):

   - [x] ```GET /api/v1/shorten_urls/{hash}``` - переход по ссылке (302 redirect).

#

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell):

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
