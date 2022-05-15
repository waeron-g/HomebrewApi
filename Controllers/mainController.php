<?php


class mainController
{
    protected $classModel = "";
    protected $model = null;

    public function  __construct()
    {
        require  __DIR__."/../Models/".$this->classModel.".php";
        $this->model = new $this->classModel;
    }

    public function   actionIndex()
    {
        $model = new $this->model;
        return $model->get(0);
    }

    public function   actionGet($id)
    {
        $model = new $this->model;
        return $model->get($id);
    }

    public function actionAdd()
    {
        $model = new $this->model;
        return $model->post();
    }

    public function actionUpdate($id)
    {
        $model = new $this->model;
        return $model->update();
    }

}