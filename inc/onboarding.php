<?php
	$WebMentionPlugin_state = '';
	$WebMentionPlugin_name = 'WebMention Plugin';
	$WebMentionPlugin_slug = 'webmention';
	$WebMentionPlugin_url = 'https://github.com/pfefferle/wordpress-webmention/blob/master/webmention.php';
	$WebMentionPlugin_installUrl = wp_nonce_url(admin_url('update.php?action=install-plugin&plugin='.$WebMentionPlugin_slug), 'doing_something', '_wpnonce');
	if (function_exists($WebMentionPlugin_slug)) {
		$WebMentionPlugin_state = 'Yes, '.$WebMentionPlugin_name.' exists';
	} else {
		$WebMentionPlugin_state = 'No, <a href="'.$WebMentionPlugin_installUrl.'">'.$WebMentionPlugin_name.'</a> does not exist';
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