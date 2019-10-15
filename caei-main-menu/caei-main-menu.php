<?php
/*
Plugin Name: Caei Main Menu
Description: Simple widget for customized main menu
Version: 1.0.0
Author: CAEI
License: GPLv2 or later
*/

// INIT
function add_caei_main_menu () {
	register_widget( 'caei_main_menu' );
}
add_action( 'widgets_init', 'add_caei_main_menu' );

// WIDGET
class caei_main_menu extends WP_Widget { 
	function __construct() {
		parent::__construct(	 
			// Base ID
			'caei_main_menu', 
		 
			// Widget name
			__('CAEI Main Menu', 'caei_main_menu_widget_domain'), 
		 
			// Widget description
			array( 'description' => __( 'CAEI customized menu', 'caei_main_menu_widget_domain' ), ) 
		);
	}
	 
	// Frontend	 
	public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
		// Widget Code *** START
        ?>
        <div class="caei-widget-main-menu">
			<?php
				if ( $instance['menu_id'] ) {
					wp_nav_menu(array(
						'menu' => $instance['menu_id'],
					));
				}            
			?>            
        </div>
        <?php
        // Widget Code *** END

		echo $args['after_widget'];
	}
	         
	// Backend
	public function form( $instance ) {
		if ( isset( $instance[ 'menu_id' ] ) ) { $menu_id = $instance[ 'menu_id' ]; }
		if ( isset( $instance[ 'menu_type' ] ) ) { $menu_type = $instance[ 'menu_type' ]; }

		$nav_menu_types = array (
			new Nav_menu_type_obj('Inline', 1),
			new Nav_menu_type_obj('Vertical', 2),
			new Nav_menu_type_obj('Dropdown', 3),
		);

        // Form
        $nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
        ?>
		<fieldset>
			<label for="<?php echo $this->get_field_id( 'menu_id' ); ?>"><b>Menu:</b></label>
			<select
				id="<?php echo $this->get_field_id( 'menu_id' ); ?>"
				name="<?php echo $this->get_field_name( 'menu_id' ); ?>"
				>
				<?php foreach ($nav_menus as $nav_menu) { ?>
					<option value="<?php echo $nav_menu->term_id; ?>" <?php if ($menu_id == $nav_menu->term_id) echo "selected"; ?>>
						<?php echo $nav_menu->name; ?>
					</option>
				<?php } ?>
			</select>
		</fieldset>
		<fieldset>
			<label for="<?php echo $this->get_field_id( 'menu_type' ); ?>"><b>Type:</b></label>
			<select
				id="<?php echo $this->get_field_id( 'menu_type' ); ?>"
				name="<?php echo $this->get_field_name( 'menu_type' ); ?>"
				>
				<?php foreach ($nav_menu_types as $nav_menu_type) { ?>
					<option value="<?php echo $nav_menu_type->value ?>"><?php echo $nav_menu_type->name ?></option>
				<?php } ?>
			</select>
		</fieldset>
        <?php
	}
	     
	// Update Process
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['menu_id'] = ( ! empty( $new_instance['menu_id'] ) ) ? strip_tags( $new_instance['menu_id'] ) : '';
		$instance['menu_type'] = ( ! empty( $new_instance['menu_type'] ) ) ? strip_tags( $new_instance['menu_type'] ) : '';
		return $instance;
	}
}

// External Classes
class Nav_menu_type_obj {
	public $name;
	public $value;
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}
}