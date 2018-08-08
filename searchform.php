<form action="/" method="get">
  <div class="input-group mb-3">
    <input type="text" name="s" id="search" class="form-control" value="<?php the_search_query(); ?>" placeholder="<?php _e('Search'); ?>" aria-label="<?php _e('Search'); ?>" aria-describedby="<?php _e('Search Form Field'); ?>">
    <div class="input-group-append">
      <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
    </div>
  </div>
</form>


