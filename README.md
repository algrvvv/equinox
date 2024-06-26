<p align="center">
<img style="width: 400px; display: flex; margin: 0 auto; justify-content: center; align-items: center;" src="https://i.pinimg.com/originals/2c/d1/08/2cd108ddb22b2068aa00d3b4c864986d.png">
</p>
<p align="center">
    <a style="margin: 3px"> 
        <img src="https://img.shields.io/badge/Framework%20-6A4BE5" style="max-width: 100%;">
    </a>
    <a style="margin: 3px">
        <img src="https://img.shields.io/badge/Equinox-v1.6.14-blue" style="max-width: 100%;">
    </a>
    <a style="margin: 3px"> 
        <img src="https://img.shields.io/badge/PHP%20-8A2BE2" style="max-width: 100%;">
    </a>
</p>

# Equinox - PHP Framework

Equinox - PHP Framework, который разработан в целях обучения и понимания работы, как PHP, так и всей веб-разработки.
Полная инструкция по установке и работе с данным фреймворком вы найдете в репозитории этого проекта.
Проект будет обновляться и всегда ждет ваших предложений и исправлений!

## Установка

### Первый способ (докер):

```bash
#клонирование проекта
git clone https://github.com/algrvvv/equinox.git
cd equinox
#создайте и по надобности измените .env файл
cp .env.example .env
```

```dotenv
DB_DRIVER=mysql / pgsql
DB_HOST=equinox_mysql / equinox_pgsql
DB_PORT=3306 / 5432
DB_NAME=db_equinox
DB_USERNAME=root / postgres
DB_PASSWORD=root / postgres
```

```bash
#перейдите в директорию с установщиком
cd _docker/setup
#запустите его в рут правами
./docker-setup.sh
```

После этого можете перейти по адресу http://localhost:8876/, где 
будет находиться ваш сервер

### Второй способ (без докера)

```bash
#клонирование проекта
git clone https://github.com/algrvvv/equinox.git
cd equinox
cp .env.example .env
#установка composer и всех зависимостей
composer install
#установка npm
npm install
```
Скопируйте и переименуйте файл `.env.example` в `.env`.
После этого измените данные для подключения в нем на свои:

```dotenv
DB_DRIVER=db_driver
DB_HOST=db_host
DB_PORT=db_port
DB_NAME=db_name
DB_USERNAME=db_user
DB_PASSWORD=db_password
```

Для подключения `sqlite` используйте одну строчку: 

```dotenv
DB_DRIVER=sqlite
```
Будет автоматически создана бд `app/database/database.sqlite`.

После подключения базы данных напишите в терминале в корне проекта:

```bash
#для переноса всех миграций
php master migrate
```

Включите отображение стилей `tailwind`:

```bash
#подключение стилей tailwind
npx tailwindcss -i ./public/assets/style.css -o ./public/dist/style.css --watch
#->                |  путь к вашим стилям |     | путь к ново созданном стилям |
# ! подключать нужно созданные стили tailwind !
```

## Маршрутизация

Создание нового маршрута происходит в `routes/web.php`. Пример подобного роута:

```php
#пример маршрута
Route::get('/', [HomeController::class, 'index']);
```

Класс `Route` поддерживает `get`, `post`, `put`, `patch`, `delete` методы, а также
метод `middleware`.


## Отображение

Обязательно храните данные для отображения в папке `views/`, а так же
файл должен иметь в расширение `.view.php`. Если вы положили такой файл
к примеру в `views/pages/page.view.php`, то в момент вызова данного представления
нужно указать такой путь: `pages/page`. И он будет успешно загружен.

#### @include

Так же, есть возможность загружать одни компоненты внутри других.
Для этого используйте в своем `.view.php`:
```php
#указать существующее название отображения
@include('pages/page')
```

## Master

Master - это небольшая **вспомогательная утилита**, которая
создаст вам файлы миграций, моделей и контроллеров.

А также она сможет перенести эти самые миграции в вашу
ранее подключенную базу данных.

Пример по ее использованию:
```bash
#чтобы увидеть все возможные команды
php master --help

#чтобы создать новый файл миграций вместе с моделью (из-за флага -m)
php master create:migration users -m

#чтобы сделать перенос миграция
php master migrate
```

## Контроллеры

```bash
#создание контроллера
php master create:controller name
```

Контроллер служит для привязки к конкретному маршруту,
а также для его обработки. Всеми известная модель MVC.

## Миграции

```bash
#создание миграции (флаг -m и для создания модели)
#про модели читать пункт `Модели и работа с бд`
php master create:migration name
```

Миграции относятся к разряду файлов, которые отвечают
за работу с базой данных.

Миграции нужны для более удобного создания нужной таблицы.

> ОЧЕНЬ ВАЖНО!

Важно отметить, что на данный момент они поддерживают
работу с MySQL, Sqlite, PostgreSQL, но в следующих обновлениях планируется
добавить поддержку MongoDB, MariaDB и др.

> Название миграций генерирует в начале дополнительные
> символы и цифры, чтобы избежать возможных конфликтов.
> А уже после само название миграции.

В миграции `users` уже есть пример того, какие поля и какие
возможности есть у миграций.

Типы данных, которые доступны при работе с миграциями:

```php
# MySQL, Sqlite, PostgreSQL
$this->table->string('field')->unique();
$this->table->integer('field');
$this->table->float('field');
$this->table->text('field')->default('test message');
$this->table->date('field');
$this->table->timestamps();

# MySQL, Sqlite
$this->table->id();

# PostgreSQL
$this->table->bigserial();
$this->table->hstore('field');
$this->table->jsonb('field');
```

После создания и редактирования всех миграций, чтобы они
были записаны в базу данных нужно вызвать 
утилиту <a href="#master">master</a>.

```bash
php master migrate
```

Если все правильно, то в консоли вы увидите 
соответствующее сообщение, а в базе данных нужные таблицы

## Модели и работа с базами данных

```bash
#создание миграции и модели одновременно
php master create:migration name -m

#отдельное создание модели
php master create:model name
```

Для начала нужно изменить данные подключения в `.env`, как 
было показано в <a href="#Установка">самом начале</a>.

После этого вам понадобиться модель, которая будет иметь в себе нужные поля:
```php
#пример с моделью User
public string $login = '';
public string $email = '';
#и тд
```

Также обязательным яв-ся функция `rules()`, которая нужна
для дальнейшей обработки и валидации данных. Функция должна
возвращать ассоциативный массив с правилами. 

Какие существуют правила?
```
required   -> поле обязательное
email      -> поле должно быть типа email
unique     -> поле должно быть уникальным (по бд)
[min => x] -> минимальное кол-во символов, где x - кол-во символов
[max => x] -> аналогично min, но для максимального кол-ва символов
```

В функции `tableName()` нужно оставить название свое таблицы,
к которой привязана модель.

```php
protected function tableName(): string
{
    return 'users';
}
```

После правильной настройки в нужном методе нашего контроллера
создаем новый экземпляр модели.

```php
$user = new User();

#пример выборки из бд
$user->select('login')->where(['id' => 3])->get();
```

> При установке проекта уже будут созданы
`LoginController` и `RegisterController` по пути
`app/controllers/` - контроллеры, в которых
> можно посмотреть примеры работы с моделью.

## А что дальше?

Творите! И конечно, жду вашего фидбека. 
Все пожелания, недочеты и исправления.
