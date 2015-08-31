# Laravel User

Package quản lý User cho Laravel Application

## Install

* **Thêm vào file composer.json của app**
```json
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/minhbang/laravel-user"
        }
    ],
    "require": {
        "minhbang/laravel-user": "dev-master"
    }
```
``` bash
$ composer update
```

* **Thêm vào file config/app.php => 'providers'**
```php
	Minhbang\LaravelUser\UserServiceProvider::class,
```

* **Publish config và database migrations**
```bash
$ php artisan vendor:publish
$ php artisan migrate
```

* **Sữa file config/auth.php**
```php
//Thay
'model' => App\User::class,
//Bằng
'model' => Minhbang\LaravelUser\User::class,
```

* **Database Seeder**
```php
<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Minhbang\LaravelUser\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // admin
        User::create(
            [
                'name'     => 'Administrator',
                'username' => 'admin',
                'email'    => 'admin@domain.com',
                'password' => 'admin',
            ]
        );
        Model::reguard();
    }
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
