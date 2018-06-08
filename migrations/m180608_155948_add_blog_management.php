<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m180608_155948_add_blog_management
 */
class m180608_155948_add_blog_management extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		$db = \Yii::$app->db;
		$query = new Query();
        if ($db->schema->getTableSchema("{{%auth_item}}", true) !== null)
		{
			if (!$query->from('{{%auth_item}}')->where(['name' => '/blog/admin/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/blog/admin/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => '/blog/category-admin/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/blog/category-admin/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => '/blog/translation/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/blog/translation/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => '/blog/category-translation/*'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> '/blog/category-translation/*',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'blog_management'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'blog_management',
					'type'			=> 2,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'blog_manager'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'blog_manager',
					'type'			=> 1,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
			if (!$query->from('{{%auth_item}}')->where(['name' => 'administrator'])->exists())
				$this->insert('{{%auth_item}}', [
					'name'			=> 'administrator',
					'type'			=> 1,
					'created_at'	=> time(),
					'updated_at'	=> time()
				]);
		}
        if ($db->schema->getTableSchema("{{%auth_item_child}}", true) !== null)
		{
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'blog_management', 'child' => '/blog/admin/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'blog_management',
					'child'		=> '/blog/admin/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'blog_management', 'child' => '/blog/category-admin/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'blog_management',
					'child'		=> '/blog/category-admin/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'blog_management', 'child' => '/blog/translation/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'blog_management',
					'child'		=> '/blog/translation/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'blog_management', 'child' => '/blog/category-translation/*'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'blog_management',
					'child'		=> '/blog/category-translation/*'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'blog_manager', 'child' => 'blog_management'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'blog_manager',
					'child'		=> 'blog_management'
				]);
			if (!$query->from('{{%auth_item_child}}')->where(['parent' => 'administrator', 'child' => 'blog_manager'])->exists())
				$this->insert('{{%auth_item_child}}', [
					'parent'	=> 'administrator',
					'child'		=> 'blog_manager'
				]);
		}
        if ($db->schema->getTableSchema("{{%auth_assignment}}", true) !== null)
		{
			if (!$query->from('{{%auth_assignment}}')->where(['item_name' => 'administrator', 'user_id' => 1])->exists())
				$this->insert('{{%auth_assignment}}', [
					'item_name'	=> 'administrator',
					'user_id'	=> 1,
					'created_at' => time()
				]);
		}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
		// it's not safe to remove auth data in migration down
    }
}
