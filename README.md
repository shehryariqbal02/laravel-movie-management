# Movie Management
The Movies API allows you to manage a collection of movies with CRUD operations.
It supports creating, retrieving, updating, and deleting movie records,
including uploading and managing cover images. Authentication is required for all operations
via Bearer token, ensuring secure access to the API.

# Getting Started

### Prerequisites

- PHP  : <a href="https://www.php.net/manual/en/install.php" target="_blank"> PHP Documentation</a>
- Composer : <a href="https://getcomposer.org/download/" target="_blank"> Composer Documentation</a>
- Laravel : <a href="https://laravel.com/docs/10.x/installation" target="_blank"> Laravel Documentation</a>
- MySQL : <a href="https://dev.mysql.com/doc/" target="_blank"> MySQL Documentation</a>

### Built With

- PHP v8.1 <br/>
- Laravel v10x <br/>
- MySQL v8.3.0<br/>
  <br/>

### Packages (Laravel)

- Sanctum
- Swagger
- Guzzle

## Installation
```angular2html
# create environment file
# set the app name, app url, and database credentials
cp .env.example .env

# dependency install
composer install

# storage link for assets access
php artisan storage:link

# create table in database
php artisan migrate

# create default user for system
php artisan db:seed

# generate swagger api documentations
php artisan l5-swagger:generate

```

## API Documentation
###### Change url with your domain 
[Document](http://127.0.0.1:8000/api/documentation)

## Development

1. Clone the Project
2. Create your Feature Branch (`git checkout -b feature/amazing-feature`)
3. Commit your Changes (`git commit -m 'comments: Added some amazing feature'`)
4. Push to the Branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request/Merge Request

### Git Branches Naming Convention

1. New feature (`feature/new-feature`)
2. Changes in existing code (`changes/little-modification-readme-file`)
3. Resolved bug (`bug/required-field-validator-not-working`), Add git message with how issues has been resolved.
