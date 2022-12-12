# Simple Laravel Web Application
Laravel driven blog with basic admin panel.

### System Requirements
* PHP 8.0 (or later)
* Install/enable the following PHP extensions: curl, fileinfo, gd, gettext, intl, mbstring, openssl, pdo_mysql, pdo_sqlite

### Installation

- Clone this repository to the destination folder
```
git clone https://github.com/itlat-web/basic-laravel-project.git .
```
- Create database named laravel (use your MySQL server)
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
```

### Application Links
- Admin panel link (default email - john.smith@gmail.com, password - 12345678): http://laravel/admin/login
- Password reset link (only for those who set MAIL parameters inside <strong>.env</strong> file): http://laravel/password/reset
