<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m190107_142301_add_bolg_versioning
 */
class m190107_142301_add_bolg_versioning extends Migration
{
    /**
     * {@inheritdoc}
     */
     public function safeUp()
     {
         $this->createTable('{{%eg_blog_new}}', [
           'id' => $this->integer(11)->notNull(),
           'version' => $this->integer(11)->notNull(),
           'category_id' => $this->integer(11),
           'update_time' => $this->timestamp(),
           'creation_time' => $this->timestamp(),
           'archive_time' => $this->timestamp(),
           'views' => $this->integer(11)->defaultValue(0),
           'thumb' => $this->string(15)->notNull()->defaultValue('default.png'),
           'author_id' => $this->integer(11),
           'status' => $this->smallInteger(4)->notNull()->defaultValue(0),
           'PRIMARY KEY (`id`, `version`)'
           ]);
           $this->createTable('{{%eg_blog_translation_new}}',[
             'blog_id' => $this->integer(11)->notNull(),
             'version' => $this->integer(11)->notNull(),
             'language' => $this->string(5)->notNull(),
             'title' => $this->string(255),
             'subtitle' => $this->string(255),
             'intro' => $this->text(),
             'description' => $this->text(),
             'PRIMARY KEY (`blog_id`, `version`, `language`)'
           ]);

         $db = \Yii::$app->db;
         $query = new Query();
         if ($db->schema->getTableSchema("{{%eg_blog}}", true) !== null)
         {
           if ($query->from('{{%eg_blog}}')->exists())
             {
               $records = $query->from('{{%eg_blog}}')->all();
               foreach ($records as &$item)
               {
                 $item['version'] = 1;
               }
               $columns = array_keys($records[0]);
               if ($columns !== null && !empty($columns))
               {
                 if ($db->schema->getTableSchema("{{%eg_blog_new}}", true) !== null)
                 {
                   $this->BatchInsert('{{%eg_blog_new}}', $columns, $records);
                 }
               }
             }
         }
         if ($db->schema->getTableSchema("{{%eg_blog_translation}}", true) !== null)
         {
           if ($query->from('{{%eg_blog_translation}}')->exists())
             {
               $records = $query->from('{{%eg_blog_translation}}')->all();
               foreach ($records as &$item)
               {
                 $item['version'] = 1;
               }
               $columns = array_keys($records[0]);
               if ($columns !== null && !empty($columns))
               {
                 if ($db->schema->getTableSchema("{{%eg_blog_translation_new}}", true) !== null)
                 {
                   $this->BatchInsert('{{%eg_blog_translation_new}}', $columns, $records);
                 }
               }
             }
         }

         $this->renameTable('{{%eg_blog}}', '{{%eg_blog_v1}}');
         $this->renameTable('{{%eg_blog_new}}', '{{%eg_blog}}');
         $this->renameTable('{{%eg_blog_translation}}', '{{%eg_blog_translation_v1}}');
         $this->renameTable('{{%eg_blog_translation_new}}', '{{%eg_blog_translation}}');
         $this->addForeignKey('fk_eg_blog_category_v1', '{{%eg_blog}}', 'category_id', '{{%eg_blog_category}}', 'id', 'SET NULL', 'CASCADE');
         $this->addForeignKey('fk_eg_blog_author_v1', '{{%eg_blog}}', 'author_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
         $this->addForeignKey('fk_eg_blog_translation_v1', '{{%eg_blog_translation}}', ['blog_id', 'version'], '{{%eg_blog}}', ['id', 'version'] , 'RESTRICT', 'CASCADE');
     }

     /**
      * {@inheritdoc}
      */
     public function safeDown()
     {
       $this->dropForeignKey('fk_eg_blog_translation_v1', '{{%eg_blog_translation}}');
       $this->dropTable('{{%eg_blog_translation}}');
       $this->dropForeignKey('fk_eg_blog_category_v1', '{{%eg_blog}}');
       $this->dropForeignKey('fk_eg_blog_author_v1', '{{%eg_blog}}');
       $this->dropTable('{{%eg_blog}}');
       $this->renameTable('{{%eg_blog_v1}}', '{{%eg_blog}}');
       $this->renameTable('{{%eg_blog_translation_v1}}', '{{%eg_blog_translation}}');
     }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190107_142301_add_bolg_versioning cannot be reverted.\n";

        return false;
    }
    */
}
