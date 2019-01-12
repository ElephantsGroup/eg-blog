<?php
use yii\helpers\Url;
use elephantsGroup\blog\components\LastBlog;
use elephantsGroup\blog\components\DateList;
use elephantsGroup\blog\models\Blog;
$module = \Yii::$app->getModule('blog');
$module_relation = \Yii::$app->getModule('service-relation');
$service_id = array_keys($module_relation->services, 'Blog')[0];
?>
<header>
	<div class="header-content">
		<div class="header-content-inner">
			<h1 id="homeHeading"><?= Yii::t('app', 'Blogs List')?></h1>
			<hr>
			<p><?= Yii::t('app', 'Blogs Description')?></p>
		</div>
	</div>
</header>

<div class="blog-default-index">
	<?php
//		echo LastBlog::widget(['title'=>Yii::t('blog', 'Blog'), 'subtitle'=>' ', 'show_archive_button'=>true, 'archive_button_text'=>Yii::t('blog', 'َBlog Archive')]);
		//echo DateList::widget();
	?>
	<section id="blog" class="light-bg">
		<div class="container inner">
			<div class="row inner-top-sm">
				<?php foreach($blog as $blog_item): ?>
				<div class="col-md-8 inner-bottom-xs" style="padding-top: 30px; float: right" >
					<figure><img src=" <?= $blog_item['thumb'] ?> " alt=" <?= $blog_item['title'] ?>"></figure>
					<h4><?= $blog_item['subtitle'] ?></h4>
					<?php
						if($module->enabled_like) echo \elephantsGroup\like\components\Likes::widget(['item' => $blog_item['id'], 'service' => $service_id]);
					?>
					<h3>
					<a href="<?= Url::to(['/blog/default/view', 'id'=>$blog_item['id'], 'lang'=>$language]) ?>"><?= $blog_item['title'] ?></a>
					</h3>
					<div class="text-small"><?= $blog_item['intro'] ?></div>
					<div class="col-md-4" style="float: right; padding: 20px;" >
					<?php
						if ($module->enabled_rating) echo \elephantsGroup\starRating\components\Rate::widget(['item' => $blog_item['id'], 'service' => $service_id]);
					?>
					</div>
				</div>
				<?php endforeach;?>
			</div><!-- /.row -->
		</div><!-- /.container -->
	</section>
</div>
