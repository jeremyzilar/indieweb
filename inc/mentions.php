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
			$mention_ID = $mention->comment_ID;
			$mention_post_ID = $mention->comment_post_ID;
			$mention_author = $mention->comment_author;
			$mention_author_email = $mention->comment_author_email;
			$mention_author_url = $mention->comment_author_url;
			$mention_author_IP = $mention->comment_author_IP;
			$mention_date = $mention->comment_date;
			$mention_date_gmt = $mention->comment_date_gmt;
			$mention_body = $mention->comment_content;
			$mention_karma = $mention->comment_karma;
			$mention_approved = $mention->comment_approved;
			$mention_agent = $mention->comment_agent;
			$mention_type = $mention->comment_type;
			$mention_parent = $mention->comment_parent;
			$mention_user_id = $mention->user_id;

			echo <<< EOF
			<div class="mention">
				<p>mention_ID = $mention_ID</p>
				<p>mention_post_ID = $mention_post_ID</p>
				<p>mention_author = $mention_author</p>
				<p>mention_author_email = $mention_author_email</p>
				<p>mention_author_url = $mention_author_url</p>
				<p>mention_author_IP = $mention_author_IP</p>
				<p>mention_date = $mention_date</p>
				<p>mention_date_gmt = $mention_date_gmt</p>
				<p>mention_body = $mention_body</p>
				<p>mention_karma = $mention_karma</p>
				<p>mention_approved = $mention_approved</p>
				<p>mention_agent = $mention_agent</p>
				<p>mention_type = $mention_type</p>
				<p>mention_parent = $mention_parent</p>
				<p>mention_user_id = $mention_parent</p>


				<h5 class="mention-author">$mention_author</h5>
				<div class="mention-body">
					$mention_body
				</div>
				<footer class="entry-meta">
    			<p class="mention-date">$mention_date</p>
				</footer><!-- .entry-meta -->
				
			</div>
EOF;
		endforeach;
	?>
	<?php //comments_template(); ?>
<?php } ?>