https://github.com/LinkedInLearning/php-user-authentication-2892138

## 005-Creating the database table

### Создание пользователя и таблицы с помощью консоли

Вход в mysql

    mysql -u root
    # или с паролем
    mysql -u root -p

Посмотреть DBs

    SHOW DATABASES;

Создать БД

    CREATE DATABASE globe_bank;

Предоставление привилегий пользователю php_user

    GRANT ALL PRIVILEGES ON globe_bank.* TO 'php_user'@'localhost'; 

Может не сработать. Тогда:

Посмотреть, есть ли пользователь:

    SELECT * FROM mysql.user WHERE User = 'php_user';

    # но лучше не со всеми столбцами:
    SELECT user, host, authentication_string FROM mysql.user WHERE user = 'php_user';

Создать пользователя:

    CREATE USER 'php_user'@'localhost' IDENTIFIED BY 'secret';

Теперь:

    GRANT ALL PRIVILEGES ON globe_bank.* TO 'php_user'@'localhost';

Команда Flush Privileges в MySQL перезагружает таблицы привилегий в базе данных, чтобы изменения вступили в силу сразу, без перезагрузки или перезапуска сервера MySQL

    FLUSH PRIVILEGES;

Посмотреть привилегии:

    SHOW GRANTS FOR 'php_user'@'localhost';

Поменять выбор базы данных

    USE globe_bank;

Далее вручную вбить sql-запрос создания таблицы или пойти другим путём:

Выход и вход под пользователем

    exit;
    mysql -u php_user -p;

    USE globe_bank;

Выполнить sql-запрос по созданию таблицы из файла

    source E:\path\to\myfile.sql

Последние три команды можно выполнить за раз:

    mysql -u php_user -p globe_bank < E:\path\to\myfile.sql

Проверить таблицу (возможно потребуется перезагрузить mysql)

    USE globe_bank;
    SHOW TABLES;
    SHOW FIELDS FROM admins;

## 



