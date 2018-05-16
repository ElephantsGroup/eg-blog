<?php

use yii\db\Migration;

class m160828_100032_create_blog extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%eg_blog_category}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'logo' => $this->string(32)->notNull()->defaultValue('default.png'),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(0)
        ]);
        $this->createTable('{{%eg_blog_category_translation}}',[
            'cat_id' => $this->integer(11),
            'language' => $this->string(5)->notNull(),
            'title' => $this->string(32),
            'PRIMARY KEY (`cat_id`, `language`)'
        ]);
        $this->addForeignKey('fk_eg_blog_category_translation', '{{%eg_blog_category_translation}}', 'cat_id', '{{%eg_blog_category}}', 'id', 'RESTRICT', 'CASCADE');
        $this->createTable('{{%eg_blog}}',[
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(11),
            'update_time' => $this->timestamp(),
            'creation_time' => $this->timestamp(),
            'archive_time' => $this->timestamp(),
            'views' => $this->integer(11)->defaultValue(0),
            'thumb' => $this->string(15)->notNull()->defaultValue('default.png'),
            'author_id' => $this->integer(11),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(0)
        ]);
        $this->addForeignKey('fk_eg_blog_category', '{{%eg_blog}}', 'category_id', '{{%eg_blog_category}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_eg_blog_author', '{{%eg_blog}}', 'author_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->createTable('{{%eg_blog_translation}}',[
            'blog_id' => $this->integer(11),
            'language' => $this->string(5)->notNull(),
            'title' => $this->string(255),
            'subtitle' => $this->string(255),
            'intro' => $this->text(),
            'description' => $this->text(),
            'PRIMARY KEY (`blog_id`, `language`)'
        ]);
        $this->addForeignKey('fk_eg_blog_translation', '{{%eg_blog_translation}}', 'blog_id', '{{%eg_blog}}', 'id', 'RESTRICT', 'CASCADE');

        $this->insert('{{%eg_blog_category}}', [
            'name' => 'عمومی',
        ]);
        $this->insert('{{%eg_blog_category_translation}}', [
            'cat_id' => 1,
            'language' => 'fa-IR',
            'title' => 'عمومی',
        ]);
        $this->insert('{{%eg_blog}}', [
            'category_id' => 1,
            'thumb' => 'blog-1.png',
            'archive_time' => 1467629406,
            'creation_time' => 1467629406,
            'update_time' => 1467629406,
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_blog}}', [
            'category_id' => 1,
            'thumb' => 'blog-2.png',
            'archive_time' => 1467629406,
            'creation_time' => 1467629406,
            'update_time' => 1467629406,
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_blog}}', [
            'category_id' => 1,
            'thumb' => 'blog-3.png',
            'archive_time' => 1467629406,
            'creation_time' => 1467629406,
            'update_time' => 1467629406,
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_blog_translation}}', [
            'blog_id' => 1,
            'language' => 'fa-IR',
            'title' => 'مزایای استفاده از پلت فرم ما',
            'subtitle' => 'مزایا و نقاط قوت پلت فرم کلید',
            'intro' => 'استفاده از این پلت فرم داری فواید بسیاری است',
            'description' => '<p>این پلت فرم دارای نقاط قوت بسیاری است که عبارتند از :</p><p>نصب و راه اندازی ساده&nbsp;</p><p>انجام تنظیمات به صورت خودکار</p><p>دارای ماژولهای متعدد است</p><p>و ...</p>',
        ]);
        $this->insert('{{%eg_blog_translation}}', [
            'blog_id' => 2,
            'language' => 'fa-IR',
            'title' => 'مزایای استفاده از پلت فرم ما',
            'subtitle' => 'مزایا و نقاط قوت پلت فرم کلید',
            'intro' => 'استفاده از این پلت فرم داری فواید بسیاری است',
            'description' => '<p>این پلت فرم دارای نقاط قوت بسیاری است که عبارتند از :</p><p>نصب و راه اندازی ساده&nbsp;</p><p>انجام تنظیمات به صورت خودکار</p><p>دارای ماژولهای متعدد است</p><p>و ...</p>',
        ]);
        $this->insert('{{%eg_blog_translation}}', [
            'blog_id' => 3,
            'language' => 'fa-IR',
            'title' => 'مزایای استفاده از پلت فرم ما',
            'subtitle' => 'مزایا و نقاط قوت پلت فرم کلید',
            'intro' => 'استفاده از این پلت فرم داری فواید بسیاری است',
            'description' => '<p>این پلت فرم دارای نقاط قوت بسیاری است که عبارتند از :</p><p>نصب و راه اندازی ساده&nbsp;</p><p>انجام تنظیمات به صورت خودکار</p><p>دارای ماژولهای متعدد است</p><p>و ...</p>',
        ]);

        $this->insert('{{%auth_item}}', [
            'name' => '/blog/admin/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => '/blog/category-admin/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => '/blog/translation/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => '/blog/category-translation/*',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => 'blog_management',
            'type' => 2,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/admin/*',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/category-admin/*',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/category-translation/*',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/translation/*',
        ]);
        $this->insert('{{%auth_item}}', [
            'name' => 'blog_manager',
            'type' => 1,
            'created_at' => 1467629406,
            'updated_at' => 1467629406
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'blog_manager',
            'child' => 'blog_management',
        ]);
        $this->insert('{{%auth_item_child}}', [
            'parent' => 'super_admin',
            'child' => 'blog_manager',
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'super_admin',
            'child' => 'blog_manager',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'blog_manager',
            'child' => 'blog_management',
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => 'blog_manager',
            'type' => 1,
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/translation/*',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/category-translation/*',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/category-admin/*',
        ]);
        $this->delete('{{%auth_item_child}}', [
            'parent' => 'blog_management',
            'child' => '/blog/admin/*',
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => 'blog_management',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/blog/category-translation/*',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/blog/translation/*',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/blog/category-admin/*',
            'type' => 2,
        ]);
        $this->delete('{{%auth_item}}', [
            'name' => '/blog/admin/*',
            'type' => 2,
        ]);

        $this->dropForeignKey('fk_eg_blog_translation', '{{%eg_blog_translation}}');
        $this->dropTable('{{%eg_blog_translation}}');
        $this->dropForeignKey('fk_eg_blog_category', '{{%eg_blog}}');
        $this->dropForeignKey('fk_eg_blog_author', '{{%eg_blog}}');
        $this->dropTable('{{%eg_blog}}');
        $this->dropForeignKey('fk_eg_blog_category_translation', '{{%eg_blog_category_translation}}');
        $this->dropTable('{{%eg_blog_category_translation}}');
        $this->dropTable('{{%eg_blog_category}}');
    }
}
