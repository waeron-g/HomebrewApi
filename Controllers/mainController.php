<?php


class mainController
{
    protected $classModel = "";
    private $model = null;

    public function  __construct()
    {
        //require $this->classModel;
        //$this->model = new $this->classModel;
    }

    public function   actionIndex()
    {
        //$model = new $this->model;
        var_dump('gpkvmrpkgv');
        die;
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
        return $model->add();
    }

    public function actionUpdate($id)
    {
        $model = new $this->model;
        return $model->update();
    }

}