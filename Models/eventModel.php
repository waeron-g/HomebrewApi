 <?php

require __DIR__."\mainModel.php";

class eventModel extends mainModel
{
    protected $tableName = 'events';

    protected function fields()
    {
        return [
            "id",
            "title"
        ];
    }

    protected function extraFields()
    {
        return [
            "description",
            "time_start",
            "time_end", 
            "status",
            "adress_id",
            "image",
        ];
    }

    protected function searchFields()
    {
        return [
            "-start"  => ["type" => -1, "field" => "time_start"],
            "start"  => ["type" => 1, "field" => "time_start"],
            "title"  => ["type" => 2, "field" => "title"],
        ];
    }
    protected function rules(){
        return [
            [["title", "description", "time_start", "time_end", "status", "adress_id"], "required"],
            [["title", "description", "image"], "str"],
            [["image", "time_start", "time_end", "status", "adress_id"], "int"],
        ];
    }
}