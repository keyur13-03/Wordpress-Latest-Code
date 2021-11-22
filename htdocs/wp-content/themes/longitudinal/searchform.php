			<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
				<input type="text" value="" placeholder="<?php __the_field('search_placeholder', 'option'); ?>" name="s" id="s" /><input class="button" type="submit" id="searchsubmit" value="<?php __the_field('search', 'option'); ?>" />
			</form>