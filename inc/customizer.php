<?php
/**
 * _s Theme Customizer
 *
 * @package _s
 */


/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function _s_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	
	class Tuto_Layout_Control extends WP_Customize_Control {

		public function render_content() {
			$name = '_customize-radio-' . $this->id;
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php foreach ( $this->choices as $value => $image ) : ?>
				<?php $class_selected = ( $this->value() == $value ) ? ' of-radio-img-selected' : ''; ?>
				<label>
					<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> class="screen-reader-text" />
					<img src="<?php echo get_template_directory_uri() . '/inc/' . esc_html( $image ); ?>" class="of-radio-img-img<?php echo $class_selected; ?>"/>
				</label>

			<?php endforeach;
		}

		public function enqueue() {

			wp_enqueue_script( 'admin-customizer-control', get_template_directory_uri() . '/inc/' . 'custom-controls.js', array( 'jquery' ), '1.0.0', true );
			wp_enqueue_style( 'customizer-control-css', get_template_directory_uri() . '/inc/' . 'custom-controls.css' );		

		}
	}
	
	$wp_customize->add_section(
		'section_layout',
		array(
			'title'    => __( 'Layout', '_s' ),
			'priority' => 1,
	 	)
 	);
	
	$wp_customize->add_setting(
		'sidebar_layout',
		array(
			'default'    => 'content-sidebar',
		)
	);

	$wp_customize->add_control(
		new Tuto_Layout_Control(
			$wp_customize,
			'sidebar_layout',
			array(
				'label'   	=> __( 'Default Layout', '_s' ),
				'section' 	=> 'section_layout',
				'choices'	=> array(
					'content-sidebar' => 'layout-content-sidebar.png',
					'sidebar-content' => 'layout-sidebar-content.png',
				)
			)
		)
	);
}
add_action( 'customize_register', '_s_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function _s_customize_preview_js() {
	wp_enqueue_script( '_s_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', '_s_customize_preview_js' );
