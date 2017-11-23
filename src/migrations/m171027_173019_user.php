<?php

use yii\db\Migration;

class m171027_173019_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('user', [
            'id' => $this->primaryKey()->unique()->comment("Уникальный порядковый номер"),
            'name' => $this->string(255)->notNull()->comment("Имя"),
            'login' => $this->string(255)->notNull()->unique()->comment("Логин"),
            'email' => $this->string(255)->notNull()->unique()->comment("Логин"),
            'phone' => $this->string(255)->notNull()->unique()->comment("Логин"),
            'uid' => $this->string(255)->notNull()->unique()->comment("Уникальный код"),
            'passcode' => $this->string(255)->notNull()->comment("Пароль"),
            'status' => $this->integer(12)->notNull()->comment("Статус"),
            'add_date' =>  $this->integer(50)->notNull()->comment("Дата добавление"),
            'edit_date' => $this->integer(50)->notNull()->comment("Дата изменение"),
            'last_action' => $this->integer(50)->comment("Время последнего действия"),
        ]);
        // Создание индекса id
        $this->createIndex(
            'idx-user-id',
            'user',
            'id'
        );
        // Создание индекса uid
        $this->createIndex(
            'idx-user-uid',
            'user',
            'uid'
        );
        // Создание индекса login
        $this->createIndex(
            'idx-user-login',
            'user',
            'login'
        );
    }

    public function down()
    {
        // Удаление индекса id
        $this->dropIndex(
            'idx-user-id',
            'user'
        );
        // Удаление индекса uid
        $this->dropIndex(
            'idx-user-uid',
            'user'
        );
        // Удаление индекса login
        $this->dropIndex(
            'idx-user-login',
            'user'
        );
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m171027_173019_user cannot be reverted.\n";

        return false;
    }
    */
}
