<?php
namespace jakharbek\user\modules\admin\actions\search;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\base\Action;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use yii\Helpers\Url;
use yii\Helpers\Html;
use jakharbek\user\Bootstrap;

class SearchAction extends Action{
    public function run($id = null){
        $session = Yii::$app->session;

        $user = new User(['scenario' => User::SCENARIO_SEARCH]);
        $model = new UserForm(['scenario' => User::SCENARIO_SEARCH]);

        $model->load(Yii::$app->request->get());
        $user->attributes = $model->attributes;
        $user->search(true);
        $query = $user->query;

        $adprovider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Bootstrap::$search_pagination_page_size_admin
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ],
            ],
        ]);
        //init
        return $this->controller->render('search',compact('adprovider','model','user','session'));
    }
}