<?php

namespace Wearesho\Yii\Http\tests\Migration;

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class M190128155433CreateUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
