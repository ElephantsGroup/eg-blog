<?php
	use yii\helpers\Url;
	use elephantsGroup\blog\models\Blog;

?>

<section id="blog" class="light-bg">
	<div class="container inner">
		<div class="row">
			<div class="col-md-12 col-sm-9 center-block text-right">
				<header>
					<?php echo ($blog_title_is_link ? '<a href="' . Url::to(['/blog', 'lang'=>$language]) . '"><h2>' . $last_blog_title . '</h2></a>' : '<h2>' . $last_blog_title . '</h2>') ?>

					<?php if(!$last_blog_subtitle)
						echo "<p> $last_blog_subtitle </p>";
					?>
				</header>
			</div><!-- /.col -->
		</div><!-- /.row -->
		<div class="row inner-top-sm">
<?php
	foreach($blog as $blog_item)
	{	
		echo '<div class="col-md-12 inner-bottom-xs">' .
			'<figure><img src="' . $blog_item['thumb'] . '" alt="' . $blog_item['title'] . '"></figure>' .
			'<h4>' . $blog_item['subtitle'] . '</h4>' .
			'<h3>' .
			($blog_title_is_link ? '<a href="' . Url::to(['/blog/default/view', 'id'=>$blog_item['id'], 'lang'=>$language]) . '">' . $blog_item['title'] . '</a>' : $blog_item['title']) .
			'</h3>' .

			'<div class="text-small">' . $blog_item['intro'] . '</div>' .
		'</div><!-- /.col -->';
	}
?>
		</div><!-- /.row -->
		<?php if($show_global_more)
			echo '<div class="row text-right"><a href="' . Yii::getAlias('@web') . '/blog" class="btn btn-default">' . $global_more_text . '</a></div>';
		?>
		<?php if($show_archive_button)
			echo '<div class="row text-center"><a href="' . Yii::getAlias('@web') . '/blog/archive" class="btn btn-default">' . $archive_button_text . '</a></div>';
		?>
	</div><!-- /.container -->
</section>