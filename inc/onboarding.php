<?php

	// Profile image
	$profile_img = get_option("indieweb_profile");
	$profile = '';
	if (empty($profile_img)) {
		$profile = <<< EOF
		<div class="col-xs-12 step">
			<a href="#" class="btn btn-xs btn-primary pull-right">Add Img</a>
			<p>You'll need an image.</p>
		</div>
EOF;
	} else {
		$imgExts = array("gif", "jpg", "jpeg", "png", "tiff", "tif");
		$urlExt = pathinfo($profile_img, PATHINFO_EXTENSION);
		if (!in_array($urlExt, $imgExts)) {
    	$profile = <<< EOF
				<div class="col-xs-12 step">
					<a href="#" class="btn btn-xs btn-primary pull-right">Fix it.</a>
					<p>Make sure the image is a full path.</p>
				</div>
EOF;
		}
	}

	$WebMentionPlugin_state = '';
	$WebMentionPlugin_name = 'WebMention Plugin';
	$WebMentionPlugin_slug = 'webmention';
	$WebMentionPlugin_class = 'WebMentionPlugin';
	$WebMentionPlugin_search = admin_url('plugin-install.php?tab=search&s='.$WebMentionPlugin_slug.'&plugin-search-input=Search+Plugins');
	$WebMentionPlugin_url = 'https://github.com/pfefferle/wordpress-webmention/blob/master/webmention.php';
	$WebMentionPlugin_installUrl = wp_nonce_url(admin_url('plugin-install.php?action=install-plugin&plugin='.$WebMentionPlugin_slug), 'doing_something', '_wpnonce');

	$state = '';
	if (!class_exists($WebMentionPlugin_class)) {
		$state = <<< EOF
		<div class="col-xs-12 step">
			<a href="$WebMentionPlugin_search" class="btn btn-xs btn-primary pull-right">Install</a>
			<p>The $WebMentionPlugin_name plugin is not installed.</p>
		</div>
EOF;
	}
?>

<section id="onboarding">
	<div class="container">
		<div class="col-xs-12">
			<h3>Welcome</h3>
			<p>You'll need to take a few steps to get this up and running.</p>
		</div>
		<?php echo $profile; ?>
		<?php echo $state; ?>
		<div class="col-xs-12 foot">
			<img src="<?php echo THEME . '/img/indiewebcamp_logo_1600.png'; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="img-responsive logo" />
			<a class="close" href="#">Close <span>&#x02A2F;</span></a>
		</div>
	</div>
</section>