<?php

namespace jakharbek\user;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface{

    /*
     * Установка контроллеров для обычного сайта
     *
     * @var Установка контроллеров в модуль сайта
     * @example: ['registration_page' => 'reg'];
     * test - это модулей
     * reg - имя контроллера
     * @example: ['registration_page' => 'test/reg'];
    */
    public static $controllers = [
        'registration_page' => ['test/reg','reg'],
        'login_page' => ['test/login','login']
        ];
    /*
     * Установка сушествуюших модулей в расширение
     * ключ куда нужно установить модуль
     * для установки к модулю  ИМЯ_СУШЕСТВУЮШИХ_МОДУЛЯ / ИМЯ_МОДУЛЯ
     */
    public static $modules = [
        'test/admin' => 'jakharbek\user\modules\admin\Module',
        'test/user' => 'jakharbek\user\modules\user\Module'
    ];
    /*
     * Перенаправление после входа в систему как админ
     */
    public static $redirect_after_login_admin = ['/test/admin'];

    /*
    * Перенаправление после входа в систему
    */
    public static $redirect_after_login = ['/test/user'];

    /*
     * Разрешение для админки
     */
    public static $premission_admin_panel = "controlPanel";

    public static $views = [
        'registration_page' => '@jakhar/user/views/registration/registration',
        'login_page' => '@jakhar/user/views/login/login',
        'forgot_page' => '@jakhar/user/views/login/forgot'
    ];

    /*
     * Адрес для потверждение электронный почты
    */
    public static $verify_email_link = '/reg/verifyemail';
    public static $verify_email_param = 'verifycode';
    /*
      * Адрес для удаление токена ссылки потверждение электронный почты
    */
    public static $verify_email_delete_link = '/reg/verifyemaildelete';
    public static $verify_email_delete_param = 'verifycode';


    /*
     * Адрес для потверждение пароля
    */
    public static $verify_passcode_link = 'login/verifypasscode';
    public static $verify_passcode_param = 'verifycode';
    /*
      * Адрес для удаление токена ссылка потверждение пароля
    */
    public static $verify_passcode_delete_link = 'login/verifypasscodedelete';
    public static $verify_passcode_delete_param = 'verifycode';

    /*
     *  @var Сыылка на профиль
     */
    public static $profile_link = '/test/user/profile/';
    public static $profile_link_admin = '/test/admin/profile/';
    /*
     * @var поиск пагинация поличество на странице
     */
    public static $search_pagination_page_size = 10;
    public static $search_pagination_page_size_admin = 10;

    /*
     * @var имя компанента почты в yii
     */
    public static $mailer_component = "mailer";
    public static $email_from = "";

    /*
     * online manager
     */
    //Проверка активности пользавателя запрос на обновление активности пользавателя в секундах
    public static $online_ajax_query_duration = 1;
    //имя переменный запроса
    public static $online_query_name = "online_manager";
    //тип запроса
    public static $online_query_method = 'POST';
    //функция колбек при успешным выполнение можно задать null;
    public static $online_query_success_js_func = 'null';
    /*
     * Меню для вывода
     */
    public static $menuItems;

    //шаблон для пользавателей
    public static $layout_user = '/main';
    //шаблон для админки
    public static $layout_admin = '/main';

    const REGISTRATION_CONTROLLER = 'jakharbek\user\controllers\registration\RegistrationController';
    const LOGIN_CONTROLLER = 'jakharbek\user\controllers\login\LoginController';
    const EXT_ALIAS = '@vendor/jakharbek/jakharbek-user/src';

    public function bootstrap($app)
    {
        /*
         * Set alias
         */
        Yii::setAlias('@jakhar/user', Bootstrap::EXT_ALIAS);

        /*
         * Регестрация переводов
         */
        $this->registerTranslations();

        /*
         * Controller Reg
         * $controller_set_to = Bootstrap::$controllers['registration_page'];
         * $controller_path = 'jakharbek\user\controllers\registration\RegistrationController';
         */
        $this->setController(Bootstrap::$controllers['registration_page'],Bootstrap::REGISTRATION_CONTROLLER);

        /*
         * Controller Login
         * $controller_set_to = Bootstrap::$controllers['login_page'];
         * $controller_path = 'jakharbek\user\controllers\login\LoginController';
         */
        $this->setController(Bootstrap::$controllers['login_page'],Bootstrap::LOGIN_CONTROLLER);

        /*
         * Modules
         * Установка всех модулей
         */
        $this->setModule(Bootstrap::$modules);

        /*
         * User Handler
         */
        \jakharbek\user\models\User::handler();

        $this->registerOnlineManagerJS();

        self::$menuItems['guest'] = [
            ['label' => Yii::t('jakhar-user','Login'), 'url' => ["/".self::$controllers['login_page'][0]]],
            ['label' => Yii::t('jakhar-user','Registration'), 'url' => ["/".self::$controllers['registration_page'][0]]],
        ];
        self::$menuItems['user'] = [
            ['label' => Yii::t('jakhar-user','Profile'), 'url' => [self::$redirect_after_login[0]]],
            ['label' => Yii::t('jakhar-user','Update'), 'url' => [self::$redirect_after_login[0]."/update/"]],
            ['label' => Yii::t('jakhar-user','Search'), 'url' => [self::$redirect_after_login[0]."/search/"]],
            ['label' => Yii::t('jakhar-user','Logout'), 'url' => [self::$redirect_after_login[0]."/logout/"]],
        ];
        self::$menuItems['admin'] = [
            ['label' => Yii::t('jakhar-user','Profile'), 'url' => [self::$redirect_after_login_admin[0]]],
            ['label' => Yii::t('jakhar-user','Update'), 'url' => [self::$redirect_after_login_admin[0]."/update/"]],
            ['label' => Yii::t('jakhar-user','Search'), 'url' => [self::$redirect_after_login_admin[0]."/search/"]],
            ['label' => Yii::t('jakhar-user','Logout'), 'url' => [self::$redirect_after_login_admin[0]."/logout/"]],
        ];
    }

    /*
     * @method @private set controller in engine
     */
    private function setController($controller_set_to = [],$controller_path = ""){
        if(count($controller_set_to) > 0):
            foreach ($controller_set_to as $controller):
                if(preg_match("#/+#",$controller)):
                    $module = explode("/",$controller)[0];
                    $controller = explode("/",$controller)[1];
                    if(!Yii::$app->hasModule($module)){continue;}
                    Yii::$app->getModule($module)->controllerMap = array_merge(Yii::$app->getModule($module)->controllerMap, [$controller => $controller_path]);
                else:
                    Yii::$app->controllerMap = array_merge(Yii::$app->controllerMap,[$controller => $controller_path]);
                endif;
            endforeach;
        endif;
    }
    /*
      * @method @private set module in engine
      */
    private function setModule($modules = null){
        if($modules == null){return;}
        if(count(Bootstrap::$modules) > 0):
            foreach (Bootstrap::$modules as $module_key => $module_path):
                if(preg_match("#/+#",$module_key)):
                    $module_parent = explode("/",$module_key)[0];
                    $module_data = explode("/",$module_key)[1];
                    if(!Yii::$app->hasModule($module_parent)){continue;}
                    Yii::$app->getModule($module_parent)->setModule($module_data,$module_path);
                else:
                    Yii::$app->setModule($module_key,$module_path);
                endif;
            endforeach;
        endif;
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['jakhar-user'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => self::EXT_ALIAS.'/messages',
        ];
    }
    public function registerOnlineManagerJS(){
        $duration = self::$online_ajax_query_duration*1000;
        $query_name = self::$online_query_name;
        $query_method = strtoupper(self::$online_query_method);
        $query_success_js = self::$online_query_success_js_func;
        $script = "
        document.onlinecomponent('$query_name=ok',$query_success_js,'$query_method',$duration);";
        if(!Yii::$app->user->isGuest):
            Yii::$app->view->registerJs($script);
        endif;
    }
}