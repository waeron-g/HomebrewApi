<?php


class mainModel
{
    protected $db = null;
    protected $tableName = null;

    public function __construct()
    {
        $config = json_decode(file_get_contents(__DIR__."/../Core/Config/Config.json"));
        $this->db = new PDO(
            "mysql:dbname=".$config->dbname.";host=".$config->dbhost,
            $config->dbuser,
            $config->dbpass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    }

    protected function fields()
    {
        return [
           null
        ];
    }

    protected function extraFields()
    {
        return [
        ];
    }

    protected function rules()
    {
        return [
//          В данном методе должны указываться все поля с типами, которые необходимо заполнять или обноволять (т.е. все кроме поля id)
//          [['field1', 'field2'], 'required'/ type('integer', 'float', string)], ...
        ];
    }

    public function get($id = 0)
    {
        if ($id > 0)
            $sql = "SELECT ".$this->prepareFields()." FROM ". $this->tableName ." WHERE id = ". $id;
        else
            $sql = "SELECT ".$this->prepareFields()." FROM ". $this->tableName;
        $data = $this->db->query($sql);
        $data = $data->fetchAll(PDO::FETCH_OBJ);
        return ['code' => 200, 'data' => !empty($data) ? $data : null, 'message' => "Получены данные таблицы"];
    }

    public function post()
    {
        $postData = json_decode(file_get_contents('php://input'));

        //TODO: здесь нужно будет валидировать предварительно все поля через метод validateFields
        // Сейчас тут написан простейший пример с заполнением 2 полей без проверки

        $sql = "INSERT INTO `". $this->tableName ."` (
        title, description, image, date_start, date_end, `status`, adress_id) 
        VALUES (:title, :description, :image, :time_start, :time_end, :status, :address_id)";
        $statement = $this->db->prepare($sql);
            $statement->execute(["title" => $postData->title, "description" => $postData->description, "image" => 'null', "time_start" => time() , "time_end" => time()  + 3600, 'status' => 1, 'address_id' => 1]);
        var_dump($statement->errorInfo()); die;
        return ['code' => 200, 'data' => null, 'message' => "Данные добавлены"];
    }

    protected function prepareFields()
    {
        /*  TODO: Дописать обработку параметра extraFields и сравнивать ее с полями, возвращаемыми одноименной функцией
         *  Передаваться параметры должны в виде ?expand=Field1,Field2....
         */
        return (implode(', ', $this->fields()));
    }

    protected function validateFields()
    {
        /* TODO: Здесь нужно будет валидировать приходящие через POST и PUT запросы поля, которые нужно сравнивать с полями в методе rules
         * пример массива rules:
         *  [
         *  [['title', 'description'], 'required'], Поля title и description обязательны для заполнения
         *  [['title', 'description'], 'string'],  Поля title и description должны быть не пустой строкой
         *  [['status', 'address_id'], 'integer']] Поля status и address_id должны быть целым числом
         */
    }

}