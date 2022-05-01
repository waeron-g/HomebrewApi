<?php
namespace Core;

class Router
{
/*  https://ruseller.com/lessons.php?rub=37&id=1145 Здесь есть введение в ООП
    Здесь тебе нужно создать основную функцию,
    которая будет принимать URL запроса и определять файл, который необходимо запустить.
    Путь роутинга будет достаточно прост domain/controller/id например example/users/2
    Также необходимо понимать какой тип запроса будет приходить
     https://overcoder.net/q/66/%D0%BE%D0%BF%D1%80%D0%B5%D0%B4%D0%B5%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D1%82%D0%B8%D0%BF%D0%B0-%D0%B7%D0%B0%D0%BF%D1%80%D0%BE%D1%81%D0%B0-%D0%B2-php-get-post-put-%D0%B8%D0%BB%D0%B8-delete
    В зависимости от типа запроса должны выполняться следующие действия:
    GET: Получение данных, метод actionIndex
    GET: Получение данных, метод actionGet используется только в случае, если указан id записи
    POST: Добавление новых данных, метод actionAdd, Также данный метод должен использоваться, если изначально id записи неизвестен.
    PUT: Обновление уже существующих данных, при этом запрос должен быть вида domain/controller/id
    Delete: Удаление уже существующих данных, при этом запрос должен быть вида domain/controller/id
*/
    public static function init()
    {

        $request = parse_url("example.com".$_SERVER['REQUEST_URI']);
        $controller = self::getController($request['path']);
        $method = self::getMethod();
        $action = self::getAction($method, $request['path']);
        return self::doAction($controller, $method, $action);
    }

    private static function doAction($controller, $method, $action)
    {
        /*
         * Проверка файла и класса на существование
         * Создание экземпляра класса
         * Проверка метода на существование
         * Проверка метода на доступ по методу запроса
         * Выполнение метода
         */
        var_dump($controller);
        return self::notFound("Перепиши мой метод!");
    }

    /*
     * Здесь нужно написать функцию, которая будет определять контроллер для следующего взаимодействия
     */
    private static function getController( $path)
    {
        $path = explode("/", $path);
        $controller = $path[1];
        if(!empty($controller)){
            return $controller;
        }
        return 'Контроллер не найден';
    }

    /*
     * Здесь нужно написать метод, который будет возвращать метод запроса
     */
    private static function getMethod()
    {
        return "Method";
    }

    /*
     * здесь нужно реализовать метод, который в зависимости от метода запроса будет либо пускать в действие, либо вызывать ошибку 404
     */
    private static function getAction($method, $path)
    {
        return "Action";
    }

    public static function notFound($path)
    {
        http_response_code(404);
        return ["code" => 404, "error" => "page not found on ". $path];
    }

}