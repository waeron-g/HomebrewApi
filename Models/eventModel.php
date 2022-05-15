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
            "date_start",
            "date_end"
        ];
    }
}