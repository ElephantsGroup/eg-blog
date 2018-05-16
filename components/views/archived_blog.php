<?php
	use yii\helpers\Url;
	use elephantsGroup\blog\models\Blog;

?>

<section id="news" class="light-bg">
	<div class="container inner">
		<div class="row">
			<div class="col-md-8 col-sm-9 center-block text-center" style="float: right;">
				<header style="background-image: none; color: black;">
					<h2><?= $last_blog_title; ?></h2>
					<p><?= $last_blog_subtitle; ?></p>
				</header>
			</div><!-- /.col -->
		</div><!-- /.row -->
		<div class="row inner-top-sm">
<?php
	foreach($blog as $blog_item)
	{
		echo '<div class="col-md-4 inner-bottom-xs" style="padding-top: 30px; float: right" >' .
			'<figure><img src="' . $blog_item['thumb'] . '" alt="' . $blog_item['title'] . '"></figure>' .
			'<h4>' . $blog_item['subtitle'] . '</h4>' .
			'<h3>' . $blog_item['title'] . '</h3>' .
			'<div class="text-small">' . $blog_item['intro'] . '</div>' .
		'</div><!-- /.col -->';
	}
?>
		</div><!-- /.row -->		
	</div><!-- /.container -->
</section>