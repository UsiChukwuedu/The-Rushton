<?php
wp_nav_menu(array(
    'container' => false, // remove nav container
    'container_class' => 'left', // class of container
    'theme_location' => 'footer-nav', // menu name
    'menu_class' => 'nav-row menu row',
    'depth' => 2, // limit the depth of the nav
    'fallback_cb' => false, // fallback function (see below)
    'walker' => new Footer_Walker(),
));

class Footer_Walker extends Walker {
    public $tree_type = array( 'post_type', 'taxonomy', 'custom' );

    public $db_fields = array(
        'parent' => 'menu_item_parent',
        'id'     => 'db_id',
    );

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>\n";
        $output .= "</li>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $item_output = $args->before;
        if($args->walker->has_children){
            $item_output .= '<li class="has-sub-menu columns">';
            $item_output .= '<ul class="sub-menu">';
        }
        $item_output .= '<li class="columns">';
        $item_output .= '<a'. $attributes . $id . $value . $class_names . '>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= "</a>";
        $item_output .= "</li>\n";
        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}
