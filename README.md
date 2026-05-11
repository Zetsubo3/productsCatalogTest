### API Product Catalog
REST API для управления каталогом товаров

### Разворот приложения
#### Клонирование репозитория
- git clone
- cd project-folder
#### Установка зависимостей
- composer install
#### Копирование конфига окружения
- cp .env.example .env
#### Генерация ключа
- php artisan key:generate
#### Настрйока БД (расскоментировать в .env и указать валидные креды)
- DB_CONNECTION=
- DB_HOST=
- DB_PORT=
- DB_DATABASE=
- DB_USERNAME=
- DB_PASSWORD=
#### Запуск миграций
- php artisan migrate
#### Наполнить БД
- php artisan db:seed (создаст 10 категорий и 100 товаров)

### Использование
#### Запуск локального сервера
- php artisan serve
#### Регистрация пользователя
- php artisan user:create - возвращает Bearer токен для защищённых ендпоинтов АПИ
#### Авторизация пользователя
- php artisan user:login - возвращает Bearer токен для защищённых ендпоинтов АПИ
#### Postman коллекция
- Коллекция catalog.postman_collection.json лежит в корне репозитория

### Тесты
#### Окружение
- Настройки тестового окружения указаны в .env.testing
- По умолчанию используется sqlite (для работы в php.ini должны быть включены extension=sqlite3 и extension=pdo_sqlite)
#### Запуск теста 
- php artisan test
#### Важно
- тесты готовы обрабатывать только CACHE_STORE=redis или другой драйвер кэша с поддержкой тегов
