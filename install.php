<html>
    <head>
        <title>Install DB</title>
    </head>
    <body>
        <?php if (!($_SERVER['REQUEST_METHOD'] === 'POST')): ?>
        <form method="post">
            <p><input type="text" name="username" placeholder="Имя пользователя" autocomplete="off" required></p>
            <p><input type="password" name="passwd" placeholder="Пароль" autocomplete="off"></p>
            <p><input type="text" name="db_name" placeholder="Имя новой БД" autocomplete="off" required></p>
            <p><input type="text" name="host" placeholder="Имя Host'а" autocomplete="off" required></p>
            <p><input type="submit" value="Создать БД"></p>
        </form>
        <?php endif; ?>
    </body>
</html>

<?php

try {
    if (isset($_POST['username']) && isset($_POST['passwd']) && isset($_POST['db_name']) && isset($_POST['host'])) {
        $dbh = new PDO("mysql:host={$_POST['host']}", ($_POST['username']), $_POST['passwd'], [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dbh->exec("CREATE DATABASE `{$_POST['db_name']}`;");

        $dbh->exec("use `{$_POST['db_name']}`");

        $dbh->exec("
            CREATE TABLE `review` (
              `id` int(11)  NOT NULL AUTO_INCREMENT,
              `subject_id` int(11) NOT NULL,
              `full_name` varchar(150) NOT NULL,
              `description` text NOT NULL,
              `image` varchar(37) DEFAULT NULL,
              `likes` int(11) DEFAULT '0',
              `date` datetime NOT NULL,
              PRIMARY KEY(`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");

        $dbh->exec("
            CREATE TABLE `subject` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) NOT NULL,
              PRIMARY KEY(`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;
    
    
            INSERT INTO `subject` (`id`, `name`) VALUES
            (1, 'Благодарность'),
            (2, 'Предложение о улучшении сервиса'),
            (3, 'Жалоба');
        ");

        echo 'БД создана';
    }
} catch (PDOException $e) {;
    die("DB ERROR: ". $e->getMessage());
}