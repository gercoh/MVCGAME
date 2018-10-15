<?php

/**
 * Класс User - модель для работы с пользователями
 */
class User
{

    /**
     * Регистрация пользователя 
     * @param string $name <p>Имя</p>
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function register($name, $password)
    {

//        echo $name;
//        echo '<br>';
//        echo $password;
//        echo '<br>';
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД

        $sql1 = 'INSERT INTO users (id,login,password) '
            . 'VALUES (null,:name,:password)';
//        print_r($sql);

        // Получение и возврат результатов. Используется подготовленный запрос
        $result1 = $db->prepare($sql1);


        $result1->bindParam(':name', $name, PDO::PARAM_STR);
//        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result1->bindParam(':password', $password, PDO::PARAM_STR);

        $result1->execute();




        $iduser = 'SELECT * FROM users WHERE login = :name AND password = :password';


        // Получение результатов. Используется подготовленный запрос
        $result3 = $db->prepare($iduser);
        $result3->bindParam(':name', $name, PDO::PARAM_STR);

        $result3->bindParam(':password', $password, PDO::PARAM_STR);

        $result3->execute();

        // Обращаемся к записи
        $userid2 = $result3->fetch();


        $uniq =  $userid2['id'];



//        INSERT INTO games (id,user_id,IP,unix,win,lose,drow) VALUES (null,2,'127.0.0.1',1539506925,0,0,0)

        $ipuser = $_SERVER['REMOTE_ADDR'];
        $ip = ip2long($ipuser);
//        echo $ip;
//        echo '<br>';


        $sql2 = 'INSERT INTO games (id,user_id,IP,unix,win,lose,draw) '
            . 'VALUES (null,'.$uniq.','.$ip.','.time().',0,0,0)';

//        print_r($sql2);


      //        print_r($sql2);
//        print_r($sql);

        // Получение и возврат результатов. Используется подготовленный запрос
        $result2 = $db->prepare($sql2);

//        $result->bindParam(':ipuser', $ipuser, PDO::PARAM_INT);

        $result2->execute();

       return true;
    }


    /**
     * Редактирование данных пользователя
     * @param integer $id <p>id пользователя</p>
     * @param string $name <p>Имя</p>
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
        public static function edit($id, $login2, $password2)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "UPDATE users 
            SET login = :login, password = :password 
            WHERE id = :id";

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':login', $login2, PDO::PARAM_STR);
        $result->bindParam(':password', $password2, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @return mixed : integer user id or false
     */
    public static function checkUserData($login, $password)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM users WHERE login = :login AND password = :password';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch();

        if ($user) {
            // Если запись существует, возвращаем id пользователя
            return $user['id'];
//            echo $user['id'];
        }
        return false;
    }

    /**
     * Запоминаем пользователя
     * @param integer $userId <p>id пользователя</p>
     */
    public static function auth($userId)
    {
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user'] = $userId;
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/>
     * Иначе перенаправляет на страницу входа
     * @return string <p>Идентификатор пользователя</p>
     */
    public static function checkLogged()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];

        }

        header("Location: /user/login");
    }

    /**
     * Проверяет является ли пользователь гостем
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Проверяет имя: не меньше, чем 2 символа
     * @param string $name <p>Имя</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет телефон: не меньше, чем 10 символов
     * @param string $phone <p>Телефон</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPhone($phone)
    {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет имя: не меньше, чем 6 символов
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checklogin($login)
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет не занят ли email другим пользователем
     * @param type $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkNameExists($name)
    {
        // Соединение с БД        
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM users WHERE login = :name';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    /**
     * Возвращает пользователя с указанным id
     * @param integer $id <p>id пользователя</p>
     * @return array <p>Массив с информацией о пользователе</p>
     */
    public static function getUserById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM users WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public static function getUserGames($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM games WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public static function updateonline($id)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
//        UPDATE games SET unix=1539531420 WHERE id = 2


        $sql2 = 'UPDATE games SET unix='.time().' WHERE id = :id';

//        $sql2 = 'INSERT INTO games (unix) '. 'VALUES ('.time().') WHERE user_id = :id';

//        print_r($sql2);

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql2);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public static function userOnline()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
//        UPDATE games SET unix=1539531420 WHERE id = 2

        $wtftime = 300;

        $list = $db->query('Select * from games WHERE  unix+'.$wtftime.' > '.time().'');
//        print_r($list);

        $userlist = array();

        $i=0;

        while($row = $list->fetch())
        {
            $userlist[$i]['id']= $row['id'];
            $userlist[$i]['user_id']=$row['user_id'];
            $userlist[$i]['IP']=$row['IP'];
            $userlist[$i]['unix']=$row['unix'];
            $userlist[$i]['win']=$row['win'];
            $userlist[$i]['lose']=$row['lose'];
            $userlist[$i]['draw']=$row['draw'];
            $i++;
        }

        return $userlist;

//        print_r($sql2);

//        $sql2 = 'UPDATE games SET unix='.time().' WHERE id = :id';

//        $sql2 = 'INSERT INTO games (unix) '. 'VALUES ('.time().') WHERE user_id = :id';

//        print_r($sql2);

        // Получение и возврат результатов. Используется подготовленный запрос

    }

    public static function gameNotForOne()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
//        UPDATE games SET unix=1539531420 WHERE id = 2

        $wtftime = 300;

        $list2 = $db->prepare('Select count(id) from games WHERE  unix+'.$wtftime.' > '.time().'');
//        print_r($list2);
        $list2->execute();
        return $list2->fetch();
//        print_r($list2->setFetchMode(PDO::FETCH_ASSOC));

//        print_r($list2);
//         return $list2->execute();
//        return  $result;

//        print_r($sql2);

//        $sql2 = 'UPDATE games SET unix='.time().' WHERE id = :id';

//        $sql2 = 'INSERT INTO games (unix) '. 'VALUES ('.time().') WHERE user_id = :id';

//        print_r($sql2);

        // Получение и возврат результатов. Используется подготовленный запрос

    }

}
