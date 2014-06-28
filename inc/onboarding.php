<?php
	$WebMentionPlugin_state = '';
	$WebMentionPlugin_name = 'WebMention Plugin';
	$WebMentionPlugin_slug = 'WebMentionPlugin';
	$WebMentionPlugin_url = 'https://github.com/pfefferle/wordpress-webmention/blob/master/webmention.php';
	if (function_exists($WebMentionPlugin_slug)) {
		$WebMentionPlugin_state = 'Yes, '.$WebMentionPlugin_name.' exists';
	} else {
		$WebMentionPlugin_state = 'No, <a href="'.$WebMentionPlugin_url.'">'.$WebMentionPlugin_name.'</a> does not exist';
	}
?>

<section id="onboarding">
	<div class="container">
		<div class="col-xs-12 step">
			<?php 
				echo $WebMentionPlugin_state;
			?>
		</div>
	</div>
</section>