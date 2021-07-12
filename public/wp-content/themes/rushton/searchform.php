<form action="<?php echo home_url(); ?>" class="searchform" method="get">
    <div>
        <label for="s" class="screen-reader-text">Search for:</label>
        <input type="text" class="s" name="s" placeholder="Search..." value="<?php the_search_query(); ?>" />
        <input type="submit" value="Search" class="searchsubmit" />
    </div>
</form>