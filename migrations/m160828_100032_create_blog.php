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
			'id' => 1,
            'name' => 'عمومی',
			'status' => 1,
        ]);
        $this->insert('{{%eg_blog_category_translation}}', [
            'cat_id' => 1,
            'language' => 'fa-IR',
            'title' => 'عمومی',
        ]);
        $this->insert('{{%eg_blog_category_translation}}', [
            'cat_id' => 1,
            'language' => 'en-US',
            'title' => 'public',
        ]);
        $this->insert('{{%eg_blog}}', [
			'id' => 1,
            'category_id' => 1,
            'thumb' => 'blog-1.png',
            'archive_time' => date('Y-m-d H:i:s',time() + 108000),
            'creation_time' => date('Y-m-d H:i:s',time()),
            'update_time' => date('Y-m-d H:i:s',time()),
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_blog}}', [
			'id' => 2,
            'category_id' => 1,
            'thumb' => 'blog-2.png',
            'archive_time' => date('Y-m-d H:i:s',time() + 108000),
            'creation_time' => date('Y-m-d H:i:s',time()),
            'update_time' => date('Y-m-d H:i:s',time()),
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_blog}}', [
			'id' => 3,
            'category_id' => 1,
            'thumb' => 'blog-3.png',
            'archive_time' => date('Y-m-d H:i:s',time() + 108000),
            'creation_time' => date('Y-m-d H:i:s',time()),
            'update_time' => date('Y-m-d H:i:s',time()),
            'author_id' => 1,
            'status' => 1,
        ]);
        $this->insert('{{%eg_blog_translation}}', [
            'blog_id' => 1,
            'language' => 'fa-IR',
            'title' => 'ساخت اولین نسخه eg-cms',
            'subtitle' => 'اولین نسخه eg-cms با داشتن پلاگین های متعدد ساخته و در گیت هاب عرضه شد.',
            'intro' => 'اولین نسخه eg-cms با داشتن پلاگین های متعدد ساخته و در گیت هاب عرضه شد. پلاگین هایی مانند تاریخ و تقویم فارسی تچندزبانی, اخبار، بلاگ و ... به رایگان در دسترس هستند',
            'description' => '<p>سورس کد برنامه در <a href="https://github.com/ElephantsGroup/eg-cms">گیت هاب</a> موجود است. این بسته بر پایه ی Yii2 ساخته شده است. اطلاعات بیشتر را می توانید در <a href="http://elephantsgroup.com">وب سایت ما</a> بیابید.</p>',
        ]);
        $this->insert('{{%eg_blog_translation}}', [
            'blog_id' => 2,
            'language' => 'fa-IR',
            'title' => 'ساخت اولین نسخه eg-cms',
            'subtitle' => 'اولین نسخه eg-cms با داشتن پلاگین های متعدد ساخته و در گیت هاب عرضه شد.',
            'intro' => 'اولین نسخه eg-cms با داشتن پلاگین های متعدد ساخته و در گیت هاب عرضه شد. پلاگین هایی مانند تاریخ و تقویم فارسی تچندزبانی, اخبار، بلاگ و ... به رایگان در دسترس هستند',
            'description' => '<p>سورس کد برنامه در <a href="https://github.com/ElephantsGroup/eg-cms">گیت هاب</a> موجود است. این بسته بر پایه ی Yii2 ساخته شده است. اطلاعات بیشتر را می توانید در <a href="http://elephantsgroup.com">وب سایت ما</a> بیابید.</p>',
        ]);
        $this->insert('{{%eg_blog_translation}}', [
            'blog_id' => 3,
            'language' => 'fa-IR',
            'title' => 'ساخت اولین نسخه eg-cms',
            'subtitle' => 'اولین نسخه eg-cms با داشتن پلاگین های متعدد ساخته و در گیت هاب عرضه شد.',
            'intro' => 'اولین نسخه eg-cms با داشتن پلاگین های متعدد ساخته و در گیت هاب عرضه شد. پلاگین هایی مانند تاریخ و تقویم فارسی تچندزبانی, اخبار، بلاگ و ... به رایگان در دسترس هستند',
            'description' => '<p>سورس کد برنامه در <a href="https://github.com/ElephantsGroup/eg-cms">گیت هاب</a> موجود است. این بسته بر پایه ی Yii2 ساخته شده است. اطلاعات بیشتر را می توانید در <a href="http://elephantsgroup.com">وب سایت ما</a> بیابید.</p>',
        ]);
    }

    public function safeDown()
    {
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
