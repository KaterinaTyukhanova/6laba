<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%friend}}`.
 */
class m240620_103621_create_friend_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%friend}}', [
            'id' => $this->primaryKey()->notNull(),
            'id_sender' => $this->integer()->notNull(),
            'id_recipient' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()
        ]);

        $this->createIndex(
            'idx-friend-id_sender',
            'friend',
            'id_sender'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-friend-id_sender',
            'friend',
            'id_sender',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-friend-id_recipient',
            'friend',
            'id_recipient'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-friend-id_recipient',
            'friend',
            'id_recipient',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%friend}}');
    }
}
