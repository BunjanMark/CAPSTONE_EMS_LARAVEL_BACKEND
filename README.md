
# A Mobile and Web based Event Management System with Aspect-based Sentiment Analysis (LARAVEL BACKEND)

  

**Description:** This is the Laravel backend application for A Mobile and Web based Event Management System with Aspect-based Sentiment Analysis

-  **PHP** (v7.4 or higher)

-  **Composer**

-  **Laravel Installer**

-  **MySQL**

  

### 1.  Clone Repository

  

-  `git clone https://github.com/BunjanMark/CAPSTONE_EMS_LARAVEL_BACKEND.git`

### 2.  Install Dependencies

  

    - run command `composer install`

### 3.  Environment Configuration

    -  `cp .env.example .env` then configure the .env and run `php artisan key:generate`

### 4.  Start Laravel Server with specific channel

    -  `php artisan app:serve-project <IPv4> <port>`

### 5.  Start XAMPP application

    - start/run **Apache** and **MySQL** modules

### 6.  Update `.env` 

    - uncomment lines these inside `.env` file line 22-27 and put value in `DB_DATABASE` variable

			DB_CONNECTION=mysql
			DB_HOST=127.0.0.1
			DB_PORT=3306
			DB_DATABASE= <db_name>
			DB_USERNAME=root
			DB_PASSWORD=`
			
### 7.  Run migration
    -  execute command `php artisan migrate`
### 8.  Seed the database
    - execude command `php artisan db:refresh-seed`

