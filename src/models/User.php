<?php
namespace jakharbek\user\models;

use jakharbek\user\Bootstrap;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use \jakharbek\core\token\models\Token;
use \jakharbek\core\token\models\TokenEmail;

/*
 *@class Класс для пользавателя Active Record
 *@method @static logout для выхода пользавателя @example Yii::@app->user->identity->logout();
 * */

class User extends ActiveRecord implements IdentityInterface{

    /*
     * @const @string сценарий регестрации
     */
    const SCENARIO_REGISTRATION = "registration";

    /*
     * @const @string сценарий авторизации
     */
    const SCENARIO_LOGIN = "login";

    /*
     * @const @string сценарий для получение пароля
     */
    const SCENARIO_FORGOT_PASSCODE = "forgot_passcode";

    /*
     * @const @string сценарий для обновление данных
     */
    const SCENARIO_UPDATE = "update";

    /*
 *   @const @string сценарий для обновление данных (админ)
    */
    const SCENARIO_UPDATE_ADMIN = "update_admin";

    /*
 * @const @string сценарий для обновление данных
 */
    const SCENARIO_UPDATE_PASSCODE = "update_passcode";

    /*
* @const @string сценарий для поиска
*/
    const SCENARIO_SEARCH = "search";

    /*
     * @const @integer статус провереного пользавателя
     */
    const STATUS_VERIFIED = 1;

    /*
     * @const @integer статус не провереного пользавателя
     */
    const STATUS_NOT_VERIFIED = 0;

    /*
    * @const @integer статус заблокированого пользавателя
    */
    const STATUS_USER_BLOCKED = 2;

    /*
     * @const @string имя\название куки аунтификации
     */
    const AUTH_TOKEN_COOKIE_NAME = "auth_token_user";

    public static $statuses = [
        self::STATUS_VERIFIED => 'Verified',
        self::STATUS_NOT_VERIFIED => 'Not verified',
        self::STATUS_USER_BLOCKED => 'Blocked'
    ];


    const ONLINE = 1;
    const OFFLINE = 2;

    public static $onlines = [
        self::ONLINE => 'Online',
        self::OFFLINE => 'Offline'
    ];

    /*
     *  для поиска
     */
    public $query;
    /*
     *  пользаватель онлайн
     */
    public static $online_duration = 10;

    public function init(){
        self::$statuses = [
            self::STATUS_VERIFIED => Yii::t('jakhar-user','Verified'),
            self::STATUS_NOT_VERIFIED => Yii::t('jakhar-user','Not verified'),
            self::STATUS_USER_BLOCKED => Yii::t('jakhar-user','Blocked')
        ];

        if($this->scenario == self::SCENARIO_SEARCH):
            $this->status = null;
        endif;
    }

    public static function tableName()
    {
        return "user"; // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        $scenario = $this->getScenario();
        $behaviors = [
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['id'],
                ],
            ]
        ];

        if($scenario == self::SCENARIO_UPDATE || $scenario == self::SCENARIO_UPDATE_ADMIN || $scenario == self::SCENARIO_REGISTRATION || $scenario == self::SCENARIO_UPDATE):
            $behaviors[] = [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['add_date', 'edit_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['edit_date'],
                ],
            ];
        endif;

        return $behaviors;
    }
    public function rules()
    {
        $rules = [
            [['name', 'login', 'uid','passcode','status','email','phone'], 'required','on' => self::SCENARIO_REGISTRATION],
            [['name','login','email'],'string','max' => 255,'min' => 2,'on' => self::SCENARIO_REGISTRATION],
            [['passcode'],'string','max' => 255,'min' => 6,'on' => self::SCENARIO_REGISTRATION],
            [['login','uid','email','phone'],'unique','on' => self::SCENARIO_REGISTRATION],
            [['email'],'email','on' => self::SCENARIO_REGISTRATION],

            [['name','login','email','phone','passcode'],'required','on' => self::SCENARIO_UPDATE],
            [['name','login','email'],'string','max' => 255,'min' => 2,'on' => self::SCENARIO_UPDATE],
            [['email'],'email','on' => self::SCENARIO_UPDATE],
            [['name','login','phone','uid'],'safe','on' => self::SCENARIO_UPDATE],
            [['name','login','phone','email','status','add_date','edit_date','last_action'],'safe','on' => self::SCENARIO_SEARCH],

            [['name','login','email','phone','status'],'required','on' => self::SCENARIO_UPDATE_ADMIN],
            [['name','login','email'],'string','max' => 255,'min' => 2,'on' => self::SCENARIO_UPDATE_ADMIN],
            [['email'],'email','on' => self::SCENARIO_UPDATE_ADMIN],
            [['passcode'],'string','max' => 255,'min' => 6,'on' => self::SCENARIO_UPDATE_ADMIN],
            [['name','login','phone','uid','passcode'],'safe','on' => self::SCENARIO_UPDATE_ADMIN],
        ];
        return $rules;
    }
    public function beforeValidate()
    {
        if($this->scenario == self::SCENARIO_REGISTRATION)
        {
            //Установка уникального идентификатора пользователя
            $this->uid = Yii::$app->security->generateRandomString();
            //Установка статуса пользователя при регистрации
            $this->status = self::STATUS_NOT_VERIFIED;
            //Шифрование пароля
            $this->passcode = md5(strip_tags($this->passcode));
            //Отправка письма для потверждение электронынй почты
            $verify_url = ['link' => Bootstrap::$verify_email_link,'param' => Bootstrap::$verify_email_param];
            $delete_token_url = ['link' => Bootstrap::$verify_email_delete_link,'param' => Bootstrap::$verify_email_delete_param];
            TokenEmail::createVerifyEmailToken($this->email,$verify_url,$delete_token_url);
        }
        if($this->scenario == self::SCENARIO_UPDATE)
        {
           if($this->email !== Yii::$app->user->identity->email) {
                //Отправка письма для потверждение электронынй почты
                $verify_url = ['link' => Bootstrap::$verify_email_link,'param' => Bootstrap::$verify_email_param];
                $delete_token_url = ['link' => Bootstrap::$verify_email_delete_link,'param' => Bootstrap::$verify_email_delete_param];
                TokenEmail::createVerifyEmailToken($this->email, $verify_url, $delete_token_url);
           }
           $this->email = Yii::$app->user->identity->email;
        }
        if($this->scenario == self::SCENARIO_UPDATE_ADMIN){

            if(strlen(preg_replace('#\s#',null,$this->passcode)) > 0):
                $this->setNewPasscode($this->passcode);
            else:
                $this->passcode = self::getByUid($this->uid,null)->passcode;
            endif;

            if($this->email !== self::getByUid($this->uid,null)->email) {
                //Отправка письма для потверждение электронынй почты
                $verify_url = ['link' => Bootstrap::$verify_email_link, 'param' => Bootstrap::$verify_email_param];
                $delete_token_url = ['link' => Bootstrap::$verify_email_delete_link, 'param' => Bootstrap::$verify_email_delete_param];
                TokenEmail::createVerifyEmailToken($this->email, $verify_url, $delete_token_url);
           }
            $this->email = self::getByUid($this->uid,null)->email;
        }
        return true;
    }
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTRATION] = ['name','login','passcode','email','phone'];
        $scenarios[self::SCENARIO_UPDATE] = ['name','login','phone','email'];
        $scenarios[self::SCENARIO_UPDATE_ADMIN] = ['name','login','phone','email','passcode','status'];
        $scenarios[self::SCENARIO_SEARCH] = ['name','login','phone','email','status','add_date','update_date','last_action'];
        return $scenarios;
    }
    public function attributeLabels(){
        return [
            'name' => Yii::t('jakhar-user','Name'),
            'login' => Yii::t('jakhar-user','Username (Login)'),
            'passcode' => Yii::t('jakhar-user','Passcode'),
            'passcode_r' => Yii::t('jakhar-user','Passcode repeat'),
            'email' => Yii::t('jakhar-user','Email'),
            'phone' => Yii::t('jakhar-user','Phone'),
            'captcha' => Yii::t('jakhar-user','Captcha'),
            'search_text' => Yii::t('jakhar-user','Search Text'),
            'status' => Yii::t('jakhar-user','Status'),
            'add_date' => Yii::t('jakhar-user','Add Date'),
            'last_action' => Yii::t('jakhar-user','Is Online')
        ];
    }
    /*
     * @method @object|null получает пользавателя по логину
     * @param @string логин пользавателя
     * @param @integer статус пользавателя
    */
    public static function getByLogin($login,$status = null)
    {
        if($status !== null){
            return self::find()->where(['login' => $login,'status' => $status])->one();
        }
        return self::find()->where(['login' => $login])->one();
    }

    /*
    * @method @object|null получает пользавателя по уникальному коду
    * @param @string уникальный код(uid) пользавателя
    * @param @integer статус пользавателя
   */
    public static function getByUid($uid,$status = 1)
    {
        if($status !== null){
            return self::find()->where(['uid' => $uid,'status' => $status])->one();
        }
        return self::find()->where(['uid' => $uid])->one();
    }

    /*
    * @method @object|null проверает сушествуетли пользаватель по логину
    * @param @string логин пользавателя
    * @param @integer статус пользавателя
   */
    public static function hasByLogin($login,$status = null)
    {
        $count = self::find()->where(['login' => $login])->count();
        if($status !== null){
            $count = self::find()->where(['login' => $login,'status' => $status])->count();
        }
        if($count == 1):
            return true;
        else:
            return false;
        endif;
    }

    /*
    * @method @object|null получает  пользавателя по эмайлу
    * @param @string эмайл пользавателя
    * @param @integer статус пользавателя
   */
    public static function getByEmail($email,$status = 1){
        if($status !== null){
            return self::find()->where(['email' => $email,'status' => $status])->one();
        }
        return self::find()->where(['email' => $email])->one();
    }

    /*
    * @method @object|null проверает сушествуетли пользаватель по эмайлу
    * @param @string эмайл пользавателя
    * @param @integer статус пользавателя
   */
    public static function hasByEmail($email,$status = 1){
        if($status !== null){
            $count = self::find()->where(['email' => $email,'status' => $status])->count();
            if($count > 0):
                return true;
            endif;
        }
        $count =  self::find()->where(['email' => $email])->count();
        if($count > 0):
            return true;
        endif;
        return false;
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($uid)
    {
        return static::findOne(['uid' => $uid, 'status' => self::STATUS_VERIFIED]);
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->uid;
    }

    /*
     * Реализация абстарктоного метода интерйеса IdentityInterface
     *
    * @method @object|null получает пользавателя
    * @param @string активный токен пользавателя
    * @param @string тип подключение если естьтип ауфнтификации можно не указавать
   */
    public static function findIdentityByAccessToken($token, $type = null){
        $token_db = Token::getByToken($token);
        Token::setCookiesToken($token,self::AUTH_TOKEN_COOKIE_NAME);
        $user = self::getByUid($token_db->value);

        $token_data = Token::find()->where(['token' => $token])->one();
        $token_data->user_uid = "guest";
        if($user):$token_data->user_uid = $user->uid;endif;
        $token_data->save();

        return $user;
    }

    /*
     * Реализация абстарктоного метода интерйеса IdentityInterface
     *
    * @method @object|null получает токена (метод не используеться)
   */
    public function getAuthKey(){
       $token = self::getAuthToken();
       if(is_object($token)){return $token->token;}
       return false;
    }

    /*
    * Реализация абстарктоного метода интерйеса IdentityInterface
    *
    * @method @object|null проверает токен (метод не используеться)
    * @param @string токен
  */
    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    /*
    * @method @object|null получает текушей действуюшей токен пользавателя
    */
    public static function getAuthToken(){
        $cookies = Yii::$app->request->cookies;
        $token = $cookies->getValue(self::AUTH_TOKEN_COOKIE_NAME);
        if($token = Token::getByToken($token))
        {
            if($token->value == Yii::$app->user->identity->uid)
            {
                return $token;
            }
        }
        return false;
    }

    /*
    * Выход
    *
    * @method @boolean выход
    */
    public function logout(){
        $token = self::getAuthKey();
        Token::deactiveToken($token,'Logout');
        Token::unsetCookiesToken(self::AUTH_TOKEN_COOKIE_NAME);
        Yii::$app->user->logout();
    }

    /*
    * Метод выполнаеться в самом начани загрузки (bootstrap)
    *
    * @method (bootstrap)
    */
    public static function handler(){
        self::handlerToken();
        self::handlerAction();
    }

    /*
    * Используеться в начале загрузки для проверки токена
    *
    * @method проверка токена
    */
    public static function handlerToken(){
        $cookies = Yii::$app->request->cookies;
        if(!$cookies->has(self::AUTH_TOKEN_COOKIE_NAME)){
            if(Yii::$app->user->identity){
                Yii::$app->user->identity->logout();
            }
        }
        if(!$token = self::getAuthToken()){
            self::logout();
        }
    }
    /*
      * Используеться в начале загрузки для фиксирование последнего действие
      *
      * @method фиксирование последнего действие
      */
    public static function handlerAction(){

        if(!Yii::$app->user->isGuest):
            Yii::$app->user->identity->fixedAction();
        endif;
        if(Yii::$app->request->isAjax):
            if(Yii::$app->request->{strtolower(Bootstrap::$online_query_method)}(Bootstrap::$online_query_name))
            {
                if(!Yii::$app->user->isGuest):
                    Yii::$app->user->identity->fixedAction();
                    exit();
                endif;
            }
        endif;
    }
    public function fixedAction(){
        $this->last_action = time();
        $this->save();
    }

    /*
     * Установка статуса провереного пользавателя по электронный почты
     */
    public function verifyEmail(){
        $this->status = self::STATUS_VERIFIED;
    }

    /*
     *  Установка нового пароля для модели
    */
    public function setNewPasscode($new_passcode = null){
        $this->passcode = md5($new_passcode);
    }

    /*
     * @method @class ActiveDataProvider поиск
     */
    public function search($admin_mode = false){
        $query = self::find();
        $query->andFilterWhere(['like','login',$this->login])
            ->andFilterWhere(['like','email',$this->email])
            ->andFilterWhere(['uid' => $this->uid])
            ->andFilterWhere(['like','phone',$this->phone]);
            $query->orFilterWhere(['like','name',$this->name]);

            if(!$admin_mode):
                if($this->status === null){
                    $this->status = self::STATUS_VERIFIED;
                }
            endif;
        $date_time_picker_pattern = '#([0-9]{4}\-[0-9]{2}\-[0-9]{2}\s+[0-9]{2}\:[0-9]{2}\:[0-9]{2})\s+\-\s+([0-9]{4}\-[0-9]{2}\-[0-9]{2}\s+[0-9]{2}\:[0-9]{2}\:[0-9]{2})#';
        if(preg_match($date_time_picker_pattern,$this->add_date)):
            preg_match_all($date_time_picker_pattern,$this->add_date,$add_date);

            $add_date_from = strtotime($add_date[1][0]);
            $add_date_to = strtotime($add_date[2][0]);

            $query->andFilterWhere(['>=', 'add_date', $add_date_from]);
            $query->andFilterWhere(['<=', 'add_date', $add_date_to]);

        endif;

        $query->andFilterWhere(['status' => $this->status]);

        if($this->last_action == self::ONLINE):
            $query->andFilterWhere(['>=', 'last_action', time()-10]);
        elseif($this->last_action == self::OFFLINE):
            $query->andFilterWhere(['<=', 'last_action', time()-10]);
        endif;

        $this->query = $query;
        return $this;
    }
    /*
     * @method @string возврашает ссылку на профиль пользавателя
     */
    public function getProfileLink($is_admin_link = false){
        if($is_admin_link):
            return Url::to([Bootstrap::$profile_link_admin,'id' => $this->login]);
        endif;
        return Url::to([Bootstrap::$profile_link,'id' => $this->login]);
    }
    /*
     * @method метод рабоатет перед удаление
     */
    public function beforeDelete()
    {
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }
    /*
     * @method метод рабоатет после удаление
     */
    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
    /*
     * @method @boolean проверает на сайте ли пользаватель
     */
    public function is_online(){
        if(Yii::$app->user->isGuest){return false;}
        $last_action = $this->last_action + self::$online_duration;
        return $last_action > time();
    }
    /*
     * @method @string возврашает время последнего входа пользавателя
     */
    public function last_action(){
        $last_action = $this->last_action;
        $seconds = time()-$this->last_action;
        $minutes = round($seconds/60);
        $hours = round($minutes/60);
        $days = round($hours/24);
        if($seconds < 60){
            return $seconds .Yii::t('jakhar-user','Seconds');
        }
        if($minutes < 60){
            return $minutes .Yii::t('jakhar-user','Minutes');
        }
        if($hours < 24){
            return $hours .Yii::t('jakhar-user','Hours');
        }
        if($days < 30){
            return $days .Yii::t('jakhar-user','Days');
        }
        return date('d.m.Y H:i:s',$last_action);
    }

}