<?php
namespace jakharbek\user\modules\user\actions\search;

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


        if(Yii::$app->request->post('online_user')):
            if($session->has('online_user')):
                if($session->get('online_user') == User::ONLINE):
                    $session->set('online_user',User::OFFLINE);
                else:
                    $session->remove('online_user',User::ONLINE);
                endif;
            else:
                $session->set('online_user',User::ONLINE);
            endif;
        endif;

        $user = new User(['scenario' => User::SCENARIO_SEARCH]);
        $model = new UserForm(['scenario' => User::SCENARIO_SEARCH]);

        $model->load(Yii::$app->request->get());

        $model->name = $model->search_text;
        $model->last_action = $session->get('online_user');
        $user->attributes = $model->attributes;
        $user->search();
        $query = $user->query;

        $adprovider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Bootstrap::$search_pagination_page_size
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