# Simple Laravel Web Application
Laravel driven blog with basic admin panel.

### System Requirements
* PHP 8.1 (or later)
* Install/enable the following PHP extensions: curl, fileinfo, gd, gettext, intl, mbstring, openssl, pdo_mysql, pdo_sqlite
* Composer (dependency manager for PHP)
* GIT
* Node.js
* npm

### Installation

- Clone this repository
```
git clone https://github.com/itlat-web/basic-laravel-project.git
```
- Proceed to the project directory
```
cd basic-laravel-project
```
- Create inside <strong>database</strong> directory file <strong>db.sqlite</strong>
- Create inside project root file <strong>.env</strong> and copy inside it contents of the file <strong>.env.example</strong>
- Inside file <strong>.env</strong> set DB_CONNECTION parameter equal to <strong>sqlite</strong>
- Inside the same file <strong>.env</strong> set DB_DATABASE parameter to the absolute path of the previously created <strong>db.sqlite</strong> file
- By now DB section of the <strong>.env</strong> should look like this:
```
DB_CONNECTION=sqlite
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=C:\absolute\path\to\your\previously\created\db.sqlite\file
DB_USERNAME=
DB_PASSWORD=
```
- (Optional) If you have email sending parameters you can update <strong>.env</strong> file MAIL section with them. In this case MAIL section of the <strong>.env</strong> should look like this: 
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.email.com
MAIL_PORT=2525
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```
- Then install site dependencies by running the following commands:
```
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```
- Launch web server
```
php artisan serve
```
- Then open your browser and proceed to the http://localhost:8000 

### Application Links
- Admin panel link (default email - john.smith@gmail.com, password - 12345678): http://localhost:8000/admin/login
- Password reset link (only for those who set MAIL parameters inside <strong>.env</strong> file): http://localhost:8000/password/reset
