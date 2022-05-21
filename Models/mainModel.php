<?php


class mainModel
{
    protected $db = null;
    protected $tableName = null;
    protected $post = null;

    public function __construct()
    {
        $this->post = json_decode(file_get_contents('php://input'), true);
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

    protected function searchFields()
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
            $sql = "SELECT ".$this->prepareFields()." FROM ". $this->tableName.$this->search();
        $data = $this->db->query($sql);
        $data = $data->fetchAll(PDO::FETCH_OBJ);
        return ['code' => 200, 'data' => !empty($data) ? $data : null, 'message' => "Получены данные таблицы"];
    }

    public function post()
    {
        try {
            $this->validateFields();
        } 
        catch (Exception $e) {
            return ['code' => 403, 'data' => null, 'message' => $e->getMessage()];
        }
        

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
        $expand = $_GET['expand'];
        $expand = explode(",",$expand);
        $extra = $this->extraFields();
        $fields = [];
        foreach ($expand as $exp){
            if (in_array($exp, $extra)){
                $fields[] = $exp;
            }
        }
        $fields =  array_merge($this->fields(), $fields);
        return implode(", ", $fields);
    }

    protected function validateFields()
    {
        $rules = $this->rules();
        foreach ($rules as $rule){
            if ($rule[1] == "required"){
                if (is_array($rule[0])){
                    foreach ($rule[0] as $rul){
                        if (!array_key_exists($rul, $this->post)){
                            throw new Exception("Не все обязательные поля заполнены");
                            return null;
                        }
                    }
                }
                else {
                    if (!array_key_exists($rule[0], $this->post)){
                        throw new Exception("Не все обязательные поля заполнены");
                        return null;
                    }
                }
            }
            if ($rule[1] == "int"){
                if (is_array($rule[0])){
                    foreach ($rule[0] as $rul){
                        if (isset( $this->post[$rul]) && (preg_match('/[^0-9]/', $this->post[$rul]))){
                            throw new Exception('Поле '.$rul. ' должно содержать только целые числа');
                            return null;
                        }
                        if (isset( $this->post[$rul])){
                            $this->post[$rul] = intval($this->post[$rul]);
                        }
                    }
                }
                else {
                    if (isset( $this->post[$rule[0]]) && (preg_match('/[^0-9]/', $this->post[$rule[0]]))){
                        throw new Exception('Поле '.$rule[0]. ' должно содержать только целые числа');
                        return null;
                    }
                    if (isset( $this->post[$rule[0]])){
                        $this->post[$rule[0]] = intval($this->post[$rule[0]]);
                    }
                }
            }
            if ($rule[1] == "flt"){
                if (is_array($rule[0])){
                    foreach ($rule[0] as $rul){
                        if (isset( $this->post[$rul]) && (filter_var($this->post[$rul], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) == null)){
                            throw new Exception('Поле '.$rul. ' должно содержать только числа');
                            return null;
                        }
                        if (isset( $this->post[$rul])){
                            $this->post[$rul] = floatval($this->post[$rul]);
                        }
                    }
                }
                else {
                    if (isset( $this->post[$rule[0]]) && (filter_var($this->post[$rule[0]], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE) == null)){
                        throw new Exception('Поле '.$rule[0]. ' должно содержать только числа');
                        return null;
                    }
                    if (isset( $this->post[$rule[0]])){
                        $this->post[$rule[0]] = floatval($this->post[$rule[0]]);
                    }
                }
            }
        }
        /* TODO: Здесь нужно будет валидировать приходящие через POST и PUT запросы поля, которые нужно сравнивать с полями в методе rules
         * пример массива rules:
         *  [
         *  [['title', 'description'], 'required'], Поля title и description обязательны для заполнения
         *  [['title', 'description'], 'string'],  Поля title и description должны быть не пустой строкой
         *  [['status', 'address_id'], 'integer']] Поля status и address_id должны быть целым числом
         */
    }

    protected function search()
    {
        $search = $_GET;
        $searchFields = $this->searchFields();
        $fields = [];
        $query = " WHERE ";
        foreach ($search as $key => $value){
            if (array_key_exists($key, $searchFields)){
                if ($searchFields[$key]["type"] == 0){
                    $fields[] = $searchFields[$key]['field'].' = '.$value;
                }
                if ($searchFields[$key]["type"] == -1){
                    $fields[] = $searchFields[$key]['field'].' < '.$value;
                }
                if ($searchFields[$key]["type"] == 1){
                    $fields[] = $searchFields[$key]['field'].' > '.$value;
                }
                if ($searchFields[$key]["type"] == 2){
                    $fields[] = "lower(".$searchFields[$key]['field']. ") LIKE lower('%".$value."%')";
                }
            }
        }
        return $query.implode(" AND ", $fields);

    }

}