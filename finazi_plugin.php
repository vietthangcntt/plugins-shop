<?php 
    /*
    Plugin Name: Recent Post Widget
    Plugin URI: https://google.com
    Description: tạo widget.
    Author: TNT
    Version: 1.0
    Author URI: http://google.com
    */
    add_action('widgets_init','creat_finazi_widget');
    function creat_finazi_widget(){
            register_widget('Finazi_Widget');
    }
    /**
     * 
     */
    class Finazi_Widget extends WP_Widget{
        
        function __construct(){
            $widget_ops = array(
                'classname' => 'widget_recent_entries',
                'description' => __( 'Your site&#8217;s most recent Posts with thumbnail', 'finazi' ),
                'customize_selective_refresh' => true,
            );
            parent::__construct('finazi_widget',__('Recent Posts Widget','finazi'),$widget_ops);
            $this->alt_option_name = 'widget_recent_entries';
        }
            public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number ) {
            $number = 5;
        }
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $rp = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ), $instance ) );
        if ( ! $rp->have_posts() ) {
            return;
        }
        ?>
        <?php echo $args['before_widget']; ?>
        <?php
        if ( isset($title) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <ul>
            <?php foreach ( $rp->posts as $recent_post ) : ?>
                <?php
                $post_title = get_the_title( $recent_post->ID );
                $title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
                ?>
                <li>
                    <!-- <?php var_dump(get_the_post_thumbnail( $recent_post->ID,'thumbnail')); ?> -->
                    <?php if (  has_post_thumbnail($recent_post->ID) || has_post_format( 'image' ) ) : ?>
                        <div class="recent_post-thumbnail"><?php echo get_the_post_thumbnail( $recent_post->ID,[92,92]); ?></div>
                    <?php endif; ?>
                    <div class="recent_post_title">
                        <a href="<?php the_permalink( $recent_post->ID ); ?>"><?php echo $title ; ?></a>
                        <span class="post-date"><?php echo get_the_time( 'F d, Y' ); ?></span>
                    </div>
                    <?php if ( $show_date ) : ?>
                        
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        echo $args['after_widget'];
    }
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }
    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
        <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:' ); ?></label>
        <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _e( 'Display post date?' ); ?></label></p>
<?php
    }
}
    // $recent = new Finazi_Widget();
    
 ?>