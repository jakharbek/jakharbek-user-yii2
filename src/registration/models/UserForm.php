<?php
namespace jakharbek\user\registration\models;
use yii\base\Model;

class UserForm extends Model{

    public $name;
    public $login;
    public $passcode;

    public function rules()
    {
        return [
            [['name','login','passcode'],'required']
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios["registration"] = ['name','login','passcode'];
        return $scenarios;
    }

}