<?php
namespace jakharbek\user\models;

use jakharbek\user\validators\update\PasscodeValidator;
use Yii;
use jakharbek\core\Bootstrap;
use yii\base\Model;
use \jakharbek\user\models\User;
use \jakharbek\user\validators\login\LoginValidator;
use \jakharbek\user\validators\login\NotExsistEmailValidator;

class UserForm extends Model{

    public $name;
    public $login;
    public $email;
    public $phone;
    public $passcode;
    public $passcode_r;
    public $passcode_old;
    public $captcha;
    public $search_text;
    public $status;
    public $add_date;
    public $edit_date;
    public $uid;
    public $user;
    public $last_action;

    public function init(){
        if($this->scenario == User::SCENARIO_UPDATE):
            if(!Yii::$app->user->isGuest):
                $user = User::getByUid(Yii::$app->user->identity->uid);
                $this->user = $user;
                $this->attributes = $user->attributes;
                $this->passcode = null;
            endif;
        endif;
        if($this->scenario == User::SCENARIO_UPDATE_ADMIN):
            if(!Yii::$app->user->isGuest):
                $user = User::getByUid(Yii::$app->user->identity->uid);
                $this->user = $user;
                if((User::getByUid($this->uid,null))):
                    $user = User::getByUid($this->uid,null);
                    $this->user = $user;
                endif;
                $this->attributes = $user->attributes;
                $this->passcode = null;
            endif;
        endif;
    }
    public function rules()
    {
        return [
            //Регистрация
            [['name','login','passcode','passcode_r','email','phone'],'required','on' => User::SCENARIO_REGISTRATION],
            [['name','login','email'],'string','max' => 255,'min' => 2,'on' => User::SCENARIO_REGISTRATION],
            [['passcode','passcode_r'],'string','max' => 255,'min' => 6,'on' => User::SCENARIO_REGISTRATION],
            [['login','uid','email','phone'],'unique','targetClass' => User::className(),'on' => User::SCENARIO_REGISTRATION],
            [['email'],'email','on' => User::SCENARIO_REGISTRATION],
            [['passcode_r'], 'compare', 'compareAttribute'=>'passcode','on' => User::SCENARIO_REGISTRATION],
            //Филтер
            [['login','passcode'],'filter','filter' => 'trim'],
            //Вход
            [['login','passcode'],'required', 'on' => User::SCENARIO_LOGIN],
            [['login'],LoginValidator::className(),'on' => User::SCENARIO_LOGIN],
            //Каптча
            [['captcha'], 'captcha','captchaAction' => Bootstrap::$controllers['captcha_controller'][0]."/user"],
            //Забыли пароль?
            [['email'],'email','on' => User::SCENARIO_FORGOT_PASSCODE],
            [['email'],'required','on' => User::SCENARIO_FORGOT_PASSCODE],
            [['email'],NotExsistEmailValidator::className(),'on' => User::SCENARIO_FORGOT_PASSCODE],
             //Обновление данных
            [['name','login','email','phone','passcode'],'required','on' => User::SCENARIO_UPDATE],
            [['name','login','email'],'string','max' => 255,'min' => 2,'on' => User::SCENARIO_UPDATE],
            [['passcode'],'string','max' => 255,'min' => 6,'on' => User::SCENARIO_UPDATE],
            [['passcode'],PasscodeValidator::className(),'on' => User::SCENARIO_UPDATE],
            [['email'],'email','on' => User::SCENARIO_UPDATE],
            [['login','email','phone'],'unique', 'when' => function($model,$attribute) {
                return $model->$attribute !== $this->user->$attribute;
            },'targetClass' => User::className(),'on' => User::SCENARIO_UPDATE],
            //Обновление пароля
            [['passcode','passcode_r','passcode_old'],'required','on' => User::SCENARIO_UPDATE_PASSCODE],
            [['passcode_r'], 'compare', 'compareAttribute'=>'passcode','on' => User::SCENARIO_UPDATE_PASSCODE],
            [['passcode'],'string','max' => 255,'min' => 6,'on' => User::SCENARIO_UPDATE_PASSCODE],
            [['passcode_old'],PasscodeValidator::className(),'on' => User::SCENARIO_UPDATE_PASSCODE],
            //поиск
            [['name','login','phone','email','search_text','status','add_date','edit_date','last_action'],'safe','on' => User::SCENARIO_SEARCH],
            //Обновление данных админ
            [['name','login','email','phone','status'],'required','on' => User::SCENARIO_UPDATE_ADMIN],
            [['name','login','email'],'string','max' => 255,'min' => 2,'on' => User::SCENARIO_UPDATE_ADMIN],
            [['passcode'],'string','max' => 255,'min' => 6,'on' => User::SCENARIO_UPDATE_ADMIN],
            [['email'],'email','on' => User::SCENARIO_UPDATE_ADMIN],
            [['login','email','phone'],'unique', 'when' => function($model,$attribute) {
                return $model->$attribute !== $this->user->$attribute;
            },'targetClass' => User::className(),'on' => User::SCENARIO_UPDATE_ADMIN],
            ];
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[User::SCENARIO_REGISTRATION] = ['name','login','passcode','passcode_r','email','phone','captcha'];
        $scenarios[User::SCENARIO_LOGIN] = ['login','passcode','captcha'];
        $scenarios[User::SCENARIO_FORGOT_PASSCODE] = ['email','captcha'];
        $scenarios[User::SCENARIO_UPDATE] = ['name','login','email','phone','passcode','captcha'];
        $scenarios[User::SCENARIO_UPDATE_ADMIN] = ['name','login','email','phone','passcode','captcha','uid','status'];
        $scenarios[User::SCENARIO_SEARCH] = ['name','login','email','phone','search_text','status','add_date','edit_date','user','last_action'];
        return $scenarios;
    }
    public function getUser(){
        return User::getByLogin($this->login,User::STATUS_VERIFIED);
    }
    public function attributeLabels(){
        $labels = [
            'name' => Yii::t('jakhar-user','Name'),
            'login' => Yii::t('jakhar-user','Username (Login)'),
            'passcode' => Yii::t('jakhar-user','Passcode'),
            'passcode_r' => Yii::t('jakhar-user','Passcode repeat'),
            'passcode_old' => Yii::t('jakhar-user','Passcode Old'),
            'email' => Yii::t('jakhar-user','Email'),
            'phone' => Yii::t('jakhar-user','Phone'),
            'captcha' => Yii::t('jakhar-user','Captcha'),
            'search_text' => Yii::t('jakhar-user','Search Text'),
            'status' => Yii::t('jakhar-user','Status'),
            'add_date' => Yii::t('jakhar-user','Add Date'),
            'last_action' => Yii::t('jakhar-user','Is Online')
        ];
        return $labels;
    }

}