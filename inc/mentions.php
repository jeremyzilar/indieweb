<!-- Mentions Start here -->
<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { ?>
	<?php 
		$defaults = array(
      'orderby' => '',
      'order' => 'DESC',
      'post_id' => $post->ID,
    );
		$mentions = get_comments($defaults);

		foreach($mentions as $mention) :
			// print_r($mention);
			$mention_ID = $mention->comment_ID;
			$mention_post_ID = $mention->comment_post_ID;
			$mention_author = $mention->comment_author;
			$mention_author_email = $mention->comment_author_email;
			$mention_author_url = $mention->comment_author_url;
			$mention_author_IP = $mention->comment_author_IP;
			$mention_date = $mention->comment_date;
			$mention_date_gmt = $mention->comment_date_gmt;
			$mention_body = $mention->comment_content;
			$mention_body = str_replace('favorited this.','favorited this',$mention_body);
			$mention_karma = $mention->comment_karma;
			$mention_approved = $mention->comment_approved;
			$mention_agent = $mention->comment_agent;
			$mention_type = $mention->comment_type;
			$mention_parent = $mention->comment_parent;
			$mention_user_id = $mention->user_id;
			$mentions_meta = get_comment_meta( $mention_ID);
			$semantic_linkbacks_author_url = get_comment_meta( $mention_ID, 'semantic_linkbacks_author_url', true);
			$semantic_linkbacks_avatar = get_comment_meta( $mention_ID, 'semantic_linkbacks_avatar', true);
			$semantic_linkbacks_canonical = get_comment_meta( $mention_ID, 'semantic_linkbacks_canonical', true);
			$semantic_linkbacks_type = get_comment_meta( $mention_ID, 'semantic_linkbacks_type', true);

			echo <<< EOF
			<div class="mention">
				<p>
					<a href="$semantic_linkbacks_author_url" class="avatar"><img src="$semantic_linkbacks_avatar" alt="$mention_author"></a>
					<a href="$semantic_linkbacks_author_url" title="$mention_author">$mention_author</a> $mention_body <a href="$semantic_linkbacks_canonical" class="rel_time" datetime="$mention_date"><span>$mention_date</span></a>
				</p>
			</div>
EOF;
		endforeach;
	?>
	<?php //comments_template(); ?>
<?php } ?>