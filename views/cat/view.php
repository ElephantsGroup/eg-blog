<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use elephantsGroup\blog\models\Blog;
use elephantsGroup\blog\models\BlogCategory;
use elephantsGroup\jdf\Jdf;
use elephantsGroup\user\models\User;
use elephantsGroup\blog\models\BlogCategoryTranslation;
use yii\helpers\Url;
use elephantsGroup\follow\assets\FollowAsset;

FollowAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\Blog */
$module_cat = \Yii::$app->getModule('blog_cat');
$module = \Yii::$app->getModule('blog');
$lang = Yii::$app->language;

$this->params['breadcrumbs'][] = ['label' => Yii::t('blog', 'Blog'), 'url' => ['index', 'lang'=>Yii::$app->controller->language]];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- ============================================================= SECTION – BLOG POST ============================================================= -->
<header>
	<div class="header-content">
		<div class="header-content-inner">
			<a href="<?= Yii::getAlias('@web') ?>/blog-list/index" style="color: white"><h1 id="homeHeading"><?= Yii::t('blog_cat', 'Related Blog Categories')?></h1></a>
			<hr>
			<p><?= ($model->translationByLang ? $model->translationByLang->title : '') ?></p>

		</div>
	</div>
</header>
<section id="blog-post" class="light-bg">
	<div class="container inner-top-sm inner-bottom classic-blog no-sidebar">
		<div class="row">
			<div class="col-md-9 center-block">
					
				<div class="post">
				
					<div class="post-content">
						<div class="post-media">
							<figure>
								<img src=" <?= BlogCategory::$upload_url . $model->id . '/' . $model->logo ?>" alt="">
							</figure>
							<?php
								if($module->enabled_follow) echo \elephantsGroup\follow\components\Follows::widget(['item' => $model->id, 'service' => 1]);
							?>
						</div>
						
						<h1 class="post-title"><?= Html::encode($this->title) ?></h1>

						<ul class="post-details">
							<?php foreach ($blog_list as $item):?>
							<li>
								<h4><?= $item['subtitle'] ?></h4>
								<h3>
									<a href="<?= Url::to(['/blog/default/view', 'id'=>$item['id']]) ?>"><?= $item['title'] ?></a>
								</h3>
								<div class="text-small"><?= $item['intro'] ?></div>
							</li>
							<?php endforeach;?>
						</ul><!-- /.post-details -->
						<div class="clearfix"></div>

					</div><!-- /.post-content -->
					
				</div><!-- /.post -->
				
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section>

<!-- ============================================================= SECTION – BLOG POST : END ============================================================= -->
