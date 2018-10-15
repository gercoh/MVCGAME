<?php

/**
 * Контроллер CabinetController
 * Кабинет пользователя
 */
class CabinetController
{

    /**
     * Action для страницы "Кабинет пользователя"
     */
    public function actionIndex()
    {
        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();
//        print_r($userId);
        // Получаем информацию о пользователе из БД

        $user =  User::getUserById($userId);

        $time =  User::updateonline($userId);

        $games = User::getUserGames($userId);

        $usersonline = User::userOnline();

        $notOnegamer = User::gameNotForOne();



//        print_r($notOnegamer);




//          print_r($usersonline);




        // Подключаем вид
        require_once(ROOT . '/views/cabinet/index.php');
        return true;
    }

    /**
     * Action для страницы "Редактирование данных пользователя"
     */
    public function actionEdit()
    {
        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();
//        print_r($userId);

        // Получаем информацию о пользователе из БД
        $user = User::getUserById($userId);

        // Заполняем переменные для полей формы
        $login1 = $user['login'];
        $password1 = $user['password'];

        // Флаг результата
        $result = false;

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы редактирования
            $login2 = $_POST['login'];
            $password2 = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            // Валидируем значения
//            if (!User::checkName($name)) {
//                $errors[] = 'Имя не должно быть короче 2-х символов';
//            }
//            if (!User::checkPassword($password)) {
//                $errors[] = 'Пароль не должен быть короче 6-ти символов';
//            }

            if ($errors == false) {
                // Если ошибок нет, сохраняет изменения профиля
                $result = User::edit($userId, $login2, $password2);
            }
        }

        // Подключаем вид
        require_once(ROOT . '/views/cabinet/edit.php');
        return true;
    }

}
