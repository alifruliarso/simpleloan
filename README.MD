# Simple Loan _REST_ API

This project provides the following function:
1. Create User/Admin
2. Login User/Admin
3. Create a loan by User
4. Approve a loan by Admin
4. Pay the loan through scheduled payment

## Setup local development

### Clone the project
-
    ```sh
    git clone https://github.com/alifruliarso/simpleloan.git
    cd simpleloan
    ```

### Setup environment variable.

- Default api url : http://localhost:8000/api/
- MySQL can be accessed from host using port 33066, user=root with empty password
- For using different port, change it inside application's ```.env``` file
    ```
    APP_PORT=8000
    FORWARD_DB_PORT=33066
    ```

### Install With Docker (Sail).
-   Run the following command :

    ```shell
    cd simpleloan
    cp .env.sample .env
    make dev
    ```

### Install Without Docker
1. Change the directory, and Install the dependencies:

    ```shell
    cd simpleloan
    cp .env.sample .env
    composer install
    ```

2. Generate application key:

    ```shell
    php artisan key:generate
    ```

3. Run database migration with seeder:

    ```shell
    php artisan migrate --seed
    ```

4. Start the local server:

    ```shell
    php artisan serve
    ```



## Features
### API Documentation

-   Swagger UI : [http://localhost:8000/swagger-ui](http://localhost:8000/swagger-ui)

### Code style and lint using Laravel Pint

```shell
./vendor/bin/pint
```

### Running the Test.

```shell
php artisan test
```

### To generate the OpenAPI Specification file, run command:

```shell
composer openapi
```

### docker-compose Commands

```shell
sail up        Start the application
sail up -d     Start the application in the background
sail stop      Stop the application
sail restart   Restart the application
sail ps        Display the status of all containers
```

### **Demo User Credentials**

```
Email: john@example.com
Password: 123456
```

### **Demo Admin Credentials**

```
Email: root@admin.com
Password: 123456
```

## Credits

This starter kit is inspired by the project [Pandora](https://arifszn.github.io/pandora),
[laravel-makefile](https://github.com/kkamara/laravel-makefile/blob/main/README.md).