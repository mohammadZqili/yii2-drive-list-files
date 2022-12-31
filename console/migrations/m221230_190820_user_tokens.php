<?php

use yii\db\Migration;

/**
 * Class m221230_190820_user_tokens
 */
class m221230_190820_user_tokens extends Migration
{

    public function up()
    {
        $this->createTable('user_tokens', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unique(),
            'oauth2_token' => $this->string(255)->defaultValue(null),
            'next_page_token' => $this->text()->defaultValue(null)
        ]);
    }

    public function down()
    {
        $this->dropTable('user_tokens');
    }
}
