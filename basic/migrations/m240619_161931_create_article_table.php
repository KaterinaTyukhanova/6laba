<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article}}`.
 */
class m240619_161931_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article}}', [
            'id' => $this->primaryKey()->notNull(),
            'title' => $this->string()->notNull(),
            'hashtags' => $this->string()->notNull(),
            'content' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'views' => $this->integer(),
            'author_id' => $this->integer()
        ]);

        $this->createIndex(
            'idx-article-author_id',
            'article',
            'author_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-article-author_id',
            'article',
            'author_id',
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
        $this->dropTable('{{%article}}');
    }
}
