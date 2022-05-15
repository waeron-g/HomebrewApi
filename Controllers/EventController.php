<?php

require __DIR__."/mainController.php";

class EventController extends mainController
{

    protected $classModel = "eventModel";

    public function   actionIndex()
    {
        $model = new $this->model;
        return $model->get(0);
    }
}