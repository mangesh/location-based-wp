<form role="search" method="get" class="blank-theme-search-form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search For :' , 'blank-theme' ) ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search...', 'blank-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php esc_attr_e( 'Search for:', 'blank-theme' ); ?>" />
	</label>
	<input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'blank-theme' ) ?>" />
</form>
