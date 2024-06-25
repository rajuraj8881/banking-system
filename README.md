# Banking System

## Setup

1. Clone the repository
2. Run `composer install`
3. Set up your `.env` file
4. Install Breeze: `composer require laravel/breeze --dev`
5. Install Breeze Scaffolding: `php artisan breeze:install`
6. nstall Node.js dependencies and build assets: `npm install npm run dev` `npm run dev`
7. Run `php artisan migrate`
8. Run `php artisan serve`

## Endpoints

-   `POST /user`: Create a new user
-   `POST /login`: Login user
-   `GET /dashboard`: Show all transactions
-   `GET /deposit`: Show deposit transactions
-   `POST /deposit`: Deposit amount
-   `GET /withdrawal`: Show withdrawal transactions
-   `POST /withdrawal`: Withdraw amount
