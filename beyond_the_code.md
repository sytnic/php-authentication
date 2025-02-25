Exercise Files

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

## 011-Challenge

Способы обновления пароля при обновлении данных пользователя:

- Admins cannot update user passwords
- Separate forms for editing user and editing password
- Only hash and update password if a password was sent.  Здесь будет реализован этот вариант.

- Users must update passwords through the reset process

## 014-Preventing weak passwords

1) 
Помимо прочего, можно сохранять предыдущие хэши паролей в отдельной таблице. Далее, при смене пароля, сравнивать (password_verify()) новый хэш со старым и, если они совпадут, выдать сообщение, что пароль не может быть как бывший пароль.  

2) 
Также можно запретить пароли, утекшие в Интернет. Базу хэшей и доступ по API можно получить на  

https://haveibeenpwned.com/  

https://haveibeenpwned.com/API/v2

3) 
Исследования показали, что не надо требовать регулярную смену паролей. Это приводит к созданию слабых паролей.  
Подробнее тут  

https://www.nist.gov/publications/digital-identity-guidelines

## 015-Resetting forgotten passwords

Можно направить письмо (но не пароль) на email для сброса пароля.  

Лучший способ - отправить письмо с токеном сброса. Работает это так:  

- Пользователь делает запрос на сброс пароля для заданного username
- Сайт всегда отвечает положительно (даже если такого пользователя нет, чтобы не выдавать реальных данных для возможного хакера)
- Если пользователь существует, нужно сгенерировать токен,

      $token = md5 (uniqid (rand(), TRUE) );

сохранить токен в отдельный столбец в таблице `users` вместе с заданным пользователем, 

- В качестве опции можно также создать столбец и хранить время создания токена, чтобы в итоге ограничить его время действия

- Затем нужно отправить email, в котором будет url со встроенным токеном

      password_reset.php?token=ver678dfgd09vd

- Переход по такому url должен приводить на заранее настроенную страницу, на которой пользователь может задать новый пароль. Этот url не должен иметь контроль аутентификации, на неё должны попадать все без проверки. Проверка заключается в выданном токене. Здесь устанавливается одноразовый доступ для замены пароля.

В итоге, токен помогает заменить пароль. Но если пользователь не дойдёт до его замены, но вспомнит старый пароль, то можно использовать и старый пароль. Токен не блокирует старый пароль, пока пароль не заменён на новый.

## 016-Preventing IDOR

IDOR - insecure direct object reference, небезопасная прямая ссылка на объект.  

По факту - это когда мы забыли проконтролировать доступ к странице или ресурсу.

В проекте на некоторых страницах `require_login();` требуется вверху и потом опять вызывается внизу вместе с `/staff_header.php` . Поэтому в идеале лучше использовать 

    require_once('../../../private/staffinitialize.php');
    require_once('../../../private/initialize.php');

на каждой странице для проверки вошедшего пользователя.  
Однако для примера достаточно как есть: был применён другой приём с функцией проверки в `/staff_header.php` только для одной страницы `public\staff\index.php` .  

## 017-Using HTTPS

https://letsencrypt.org/

Курс  
SSL Certificates for Web Developers

## 018-Protecting access tokens

Способы защиты файлов куки (и сессий, на них ссылаются куки)


- Use HTTPS

- Secure session cookies in php.ini  

    // удалить куки при закрытии браузера  
    session.cookie_lifetime = 0 

    // куки отправятся только по https
    session.cookie_secure = 1  

    // Javascript не будет иметь доступа к кукам  
    session.cookie_httponly = 1  

    // Данные файла сессии будут получаться только из куков, а не из преременных GET, POST или откуда-то ещё  
    session.use_only_cookies = 1  

- Пересоздать идентификатор сессии перед каждым входом (логином) в систему

     // например, так  
     session_regenerate_id();


##
