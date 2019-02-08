# Folder description

    folder tadao-client is client-side

    folder tadao-serveur is server-side


# Database installation

    Connect you on your database and import the files bus.structure.sql and bus.data.sql or bus.sql


# For connect in your database

### location tadao-utilisateur/public/index.php and tadao-zero/public/index.php

```PHP
    $connectionParams = [
            'driver'    => 'pdo_mysql',
            'host'      => '127.0.0.1',
            'port'      => 'Your_port',
            'dbname'    => 'dbname',
            'user'      => 'dbusername',
            'password'  => 'dbPassWord',
            'charset'   => 'utf8mb4',
        ];
```
You must change port / dbname / user / password

### Start your localhost

    go to : 
    
    ```PHP

        cd tadao-utilisateur/

    ```

    Open your bash and write :

    ```PHP

        php -S localhost:8000 -t public

    ```

    Open your browser and type :

    ```PHP

        http://localhost:8000

    ```

### To insert new data

    Go to folder tadao-serveur , click on import and choose routes.csv in your desktop

