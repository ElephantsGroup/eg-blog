<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m190130_140734_add_publish_date_column_to_blog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $db = \Yii::$app->db;
      $query = new Query();
      if ($db->schema->getTableSchema("{{%eg_blog}}", true) !== null)
      {
        if ($query->from('{{%eg_blog}}')->exists())
        {
          $this->addColumn('{{%eg_blog}}', 'publish_time', $this->timestamp()->after('archive_time'));
          $this->update("{{%eg_blog}}", ['publish_time' => new \yii\db\Expression('creation_time')]);
        }
      }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('{{%eg_blog}}', 'publish_time');
    }
}
