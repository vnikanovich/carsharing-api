## Развертывание проекта

1. Скачивам зависимости

        composer install


2. Подключение к БД (использовала postgreSQL, но можно и mysql)

    2.1 Создаем новую базу:
            
        create user carsharing with password '12345';
        create database carsharing with owner carsharing;
        grant all privileges on database carsharing to carsharing;
            
    2.2 Создаем бд для тестов:
        
        create user test_carsharing with password '12345';
        create database test_carsharing with owner test_carsharing;
        grant all privileges on database test_carsharing to test_carsharing;

3. Создаем файл .env, копируя с .env.example, прописываем настройки подключения к бд:

        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5434
        DB_DATABASE=carsharing
        DB_USERNAME=carsharing
        DB_PASSWORD=12345
        
4. Генерируем уникальный ключ приложения:
        
        php artisan key:generate
        
5. Применяем миграции:
        
        php artisan migrate

6. Запускаем seeders для создания фейковых данных (если требуется)

        php artisan db:seed

7. Запускаем сервер 

        php artisan serve --port=80

8. Тесты

        php artisan test
