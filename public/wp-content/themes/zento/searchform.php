<form action="<?php echo home_url('/'); ?>" method="get" class="search-form">
    <div class="form-group">
        <input type="text" name="s" id="s" value="<?php echo get_search_query(); ?>" class="search-field inputbox large" placeholder="<?php esc_attr_e('Type to start your search', 'zento'); ?>" aria-label="<?php esc_attr_e('Type to start your search', 'zento'); ?>" required>
        <button type="submit" class="epcl-button submit absolute" aria-label="<?php esc_attr_e('Submit', 'zento'); ?>">
            <?php esc_html_e('Search', 'zento'); ?>
        </button>
    </div>
</form>