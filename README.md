P2P wallet system
================

A platform to exchange money between users made with laravel.

## Project installation

- Clone the repository
- Install php dependencies: `composer install`
- Install js dependencies: `npm install && npm run dev`
- Copy env file: `cp .env.example .env`
- Configure database in env file
- Run Database migration: `php artisan migrate`
- Generate App key: `php artisan key:generate`
- Run Seeder (will generate some test data) `php artisan db:seed`
- Set mail configuration in env file

### 3rd-party api setup instructions
- Api from https://openexchangerates.org
  - Copy your app_id from `Open Exchange Rates` account in env file

### Login credentials
- username: `user@example.com`
- password: `password`

## Contributing

Thank you for considering contributing to the project!

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
