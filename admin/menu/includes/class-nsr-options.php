<?php

/**
 * The class that maintains the default options and the related meta data.
 *
 * @note              The terms 'basic' and 'extended' were created to divide
 *                    the available options into reasonable parts. That's just it.
 * @link              https://github.com/demispatti/nicescrollr
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <demis@demispatti.ch>
 * Author URI:        http://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class nsr_options {

	/**
	 * The array holding the application keys.
	 *
	 * @since 0.1.0
	 * @acces private
	 * @var   array $keys
	 */
	private $keys;

	/**
	 *  The names of the sections.
	 *
     * @since  0.1.0
	 * @access private
	 * @var    array $settings_sections
	 */
	private $settings_sections = array( 'backend', 'frontend', 'plugin' );

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @param array $keys
	 */
	public function __construct( $keys ) {

		$this->keys = $keys;
	}

	/**
	 * Returns the basic nicescroll options and their meta data.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $basic_options
	 */
	private function basic_options() {

		$basic_options = array(
			'enabled' => array(
				//'option_key'     => 'enabled',
				'name'           => __( 'Nicescroll', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Enable or disable Nicescroll.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'cursorcolor' => array(
				//'option_key'     => 'cursorcolor',
				'name'           => __( 'Cursor Color', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Choose cursor color.', $this->keys['plugin_domain'] ),
				'frontend_value' => '#c1c1c1',
				'backend_value'  => '#c1c1c1',
				'input_type'     => 'color',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'cursoropacitymin' => array(
				//'option_key'     => 'cursoropacitymin',
				'name'           => __( 'Cursor Opacity Minimum', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set opacity for when the cursor is inactive.', $this->keys['plugin_domain'] ),
				'frontend_value' => '0.0',
				'backend_value'  => '0.0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'cursoropacitymax' => array(
				//'option_key'     => 'cursoropacitymax',
				'name'           => __( 'Cursor Opacity Maximum', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set opacity for when the cursor is active.', $this->keys['plugin_domain'] ),
				'frontend_value' => '1.0',
				'backend_value'  => '1.0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'cursorwidth' => array(
				//'option_key'     => 'cursorwidth',
				'name'           => __( 'Cursor Width', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor width (in pixel).', $this->keys['plugin_domain'] ),
				'frontend_value' => '8px',
				'backend_value'  => '16px',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'cursorborderwidth' => array(
				//'option_key'     => 'cursorborderwidth',
				'name'           => __( 'Cursor Border Width', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor border width (in pixel).', $this->keys['plugin_domain'] ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'cursorborderstate' => array(
				//'option_key'     => 'cursorborderstate',
				'name'           => __( 'Cursor Border State', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Select cursor border state.', $this->keys['plugin_domain'] ),
				'frontend_value' => 'solid',
				'backend_value'  => 'solid',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( 'solid', 'dashed', 'dotted', 'double', __( 'none', $this->keys['plugin_domain'] ) )
			),
			'cursorbordercolor' => array(
				//'option_key'     => 'cursorbordercolor',
				'name'           => __( 'Cursor Border Color', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor border color.', $this->keys['plugin_domain'] ),
				'frontend_value' => '#b6b6b6',
				'backend_value'  => '#b6b6b6',
				'input_type'     => 'color',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'cursorborderradius' => array(
				//'option_key'     => 'cursorborderradius',
				'name'           => __( 'Cursor Border Radius', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor border radius (in pixel).', $this->keys['plugin_domain'] ),
				'frontend_value' => '6px',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'autohidemode' => array(
				//'option_key'     => 'autohidemode',
				'name'           => __( 'Autohide Mode', $this->keys['plugin_domain'] ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Select auto-hide mode.', $this->keys['plugin_domain'] ),
				'frontend_value' => 'on',
				'backend_value'  => 'off',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array(
					__( 'off', $this->keys['plugin_domain'] ),
					__( 'on', $this->keys['plugin_domain'] ),
					__( 'cursor', $this->keys['plugin_domain'] )
				)
			),
		);

		return $basic_options;
	}

	/**
	 * Returns all extended nicescroll options and their meta data.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $extended_options
	 */
	private function extended_options() {

		$extended_options = array(
			'zindex' => array(
				//'option_key'     => 'zindex',
				'name'           => __( 'Z-Index', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set z-index.', $this->keys['plugin_domain'] ),
				'frontend_value' => '9999',
				'backend_value'  => '9999',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'scrollspeed' => array(
				//'option_key'     => 'scrollspeed',
				'name'           => __( 'Scroll Speed', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set scrolling speed.', $this->keys['plugin_domain'] ),
				'frontend_value' => '72',
				'backend_value'  => '72',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'mousescrollstep' => array(
				//'option_key'     => 'mousescrollstep',
				'name'           => __( 'Mouse Scroll Step', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set scrolling speed for the mousewheel.', $this->keys['plugin_domain'] ),
				'frontend_value' => '24',
				'backend_value'  => '24',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'touchbehavior' => array(
				//'option_key'     => 'touchbehavior',
				'name'           => __( 'Touch Behaviour', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable cursor-drag scrolling for touch-enabled computers.', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'hwacceleration' => array(
				//'option_key'     => 'hwacceleration',
				'name'           => __( 'Hardware Acceleration', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch hardware acceleration on or off.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'usetransition' => array(
				//'option_key'     => 'usetransition',
				'name'           => __( 'Transitions', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch transitions on or off.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'boxzoom' => array(
				//'option_key'     => 'boxzoom',
				'name'           => __( 'Box Zoom', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable zoom for box content.', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'dblclickzoom' => array(
				//'option_key'     => 'dblclickzoom',
				'name'           => __( 'Double Click Zoom', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Zoom activated when double click on box ("Box Zoom" must be "on").', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'gesturezoom' => array(
				//'option_key'     => 'gesturezoom',
				'name'           => __( 'Gesture Zoom', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Zoom on pitch in/out on box (For touch devices, set "Box Zoom" on).', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'grabcursorenabled' => array(
				//'option_key'     => 'grabcursorenabled',
				'name'           => __( 'Grab-Cursor', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Display "grab" icon ("Touch Behavior" must be possible and "on").', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			/*array(
				'option_key'               => 'background',
				'name'             => __( 'Background', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'      => __( 'Change css for rail background.', $this->keys['plugin_domain'] ),
				'frontend_value'   => '',
				'backend_value'    => '',
				'input_type' => 'text',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),*/
			'iframeautoresize' => array(
				//'option_key'     => 'iframeautoresize',
				'name'           => __( 'iFrame Autoresize', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch auto-resize for iFrames on or off.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'cursorminheight' => array(
				//'option_key'     => 'cursorminheight',
				'name'           => __( 'Cursor Minimum Height', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set the minimum cursor height.', $this->keys['plugin_domain'] ),
				'frontend_value' => '32',
				'backend_value'  => '32',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none'
			),
			'preservenativescrolling' => array(
				//'option_key'     => 'preservenativescrolling',
				'name'           => __( 'Preserve Native Scrolling', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Scroll native scrollable areas with mouse.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'railoffset' => array(
				//'option_key'     => 'railoffset',
				'name'           => __( 'Rail Offset', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Add offset for rail position or disable it.', $this->keys['plugin_domain'] ),
				'frontend_value' => 'off',
				'backend_value'  => 'off',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array(
					__( 'off', $this->keys['plugin_domain'] ),
					__( 'top', $this->keys['plugin_domain'] ),
					__( 'left', $this->keys['plugin_domain'] )
				)
			),
			'bouncescroll' => array(
				//'option_key'     => 'bouncescroll',
				'name'           => __( 'Bounce Scroll', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Mobile-like scroll bouncing at the end of content.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'spacebar' => array(
				//'option_key'     => 'spacebar',
				'name'           => __( 'Spacebar', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Page down-scrolling with the spacebar.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'railpaddingtop' => array(
				//'option_key'     => 'railpaddingtop',
				'name'           => __( 'Rail Padding Top', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding top.', $this->keys['plugin_domain'] ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'railpaddingright' => array(
				//'option_key'     => 'railpaddingright',
				'name'           => __( 'Rail Padding Right', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding right.', $this->keys['plugin_domain'] ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'railpaddingbottom' => array(
				//'option_key'     => 'railpaddingbottom',
				'name'           => __( 'Rail Padding Bottom', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding bottom.', $this->keys['plugin_domain'] ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'railpaddingleft' => array(
				//'option_key'     => 'railpaddingleft',
				'name'           => __( 'Rail Padding Left', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding left.', $this->keys['plugin_domain'] ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'disableoutline' => array(
				//'option_key'     => 'disableoutline',
				'name'           => __( 'Outline', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'For chrome browser, disable outline.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'horizrailenabled' => array(
				//'option_key'     => 'horizrailenabled',
				'name'           => __( 'Horizontal Rail', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Let Nicescroll manage horizontal scrolling.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'railalign' => array(
				//'option_key'     => 'railalign',
				'name'           => __( 'Rail Alignment Horizontal', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Select alignment of horizontal rail.', $this->keys['plugin_domain'] ),
				'frontend_value' => 'right',
				'backend_value'  => 'right',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( __( 'right', $this->keys['plugin_domain'] ), __( 'left', $this->keys['plugin_domain'] ) )
			),
			'railvalign' => array(
				//'option_key'     => 'railvalign',
				'name'           => __( 'Rail Alignment Vertical', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Select alignment of vertical rail.', $this->keys['plugin_domain'] ),
				'frontend_value' => 'bottom',
				'backend_value'  => 'bottom',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( __( 'bottom', $this->keys['plugin_domain'] ), __( 'top', $this->keys['plugin_domain'] ) )
			),
			'enabletranslate3d' => array(
				//'option_key'     => 'enabletranslate3d',
				'name'           => __( 'Translate3D', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch translate3d on or off.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'enablemousewheel' => array(
				//'option_key'     => 'enablemousewheel',
				'name'           => __( 'Mouse Wheel', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Let Nicescroll manage mousewheel events.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'enablekeyboard' => array(
				//'option_key'     => 'enablekeyboard',
				'name'           => __( 'Keyboard', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Let Nicescroll manage keyboard events.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'smoothscroll' => array(
				//'option_key'     => 'smoothscroll',
				'name'           => __( 'Smooth Scroll', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Scroll with ease movement.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'sensitiverail' => array(
				//'option_key'     => 'sensitiverail',
				'name'           => __( 'Sensitive Rail', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Scrolling trough click on rail.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'enablemouselockapi' => array(
				//'option_key'     => 'enablemouselockapi',
				'name'           => __( 'Mouse Lock API', $this->keys['plugin_domain'] ),
				'settings_group' => __( 'extended', $this->keys['plugin_domain'] ),
				'title'          => 'Use mouse caption lock API.',
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			/*array(
				'option_key'               => 'cursormaxheight',
				'name'             => __( 'Cursor Maximum Height', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'      => __( 'Switch cursor maximum height on or off.', $this->keys['plugin_domain'] ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),*/
			'cursorfixedheight' => array(
				//'option_key'     => 'cursorfixedheight',
				'name'           => __( 'Cursor Fixed Height', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Select cursor fixed height or disable it.', $this->keys['plugin_domain'] ),
				'frontend_value' => '160',
				'backend_value'  => '160',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( __( 'off', $this->keys['plugin_domain'] ), '60', '100', '160', '220', '280' )
			),
			'directionlockdeadzone' => array(
				//'option_key'     => 'directionlockdeadzone',
				'name'           => __( 'Direction Lock Dead Zone', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Choose dead zone (in pixel) for direction lock activation.', $this->keys['plugin_domain'] ),
				'frontend_value' => '6px',
				'backend_value'  => '6px',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'hidecursordelay' => array(
				//'option_key'     => 'hidecursordelay',
				'name'           => __( 'Autohide Delay', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Delay in miliseconds for fading out the scrollbar.', $this->keys['plugin_domain'] ),
				'frontend_value' => '400',
				'backend_value'  => '400',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'nativeparentscrolling' => array(
				//'option_key'     => 'nativeparentscrolling',
				'name'           => __( 'Native Parent Scrolling', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Detect bottom of content and let parent scroll, as native scroll does.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'enablescrollonselection' => array(
				//'option_key'     => 'enablescrollonselection',
				'name'           => __( 'Scroll On Selection', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Auto-scrolling of content while selecting text.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'overflowx' => array(
				//'option_key'     => 'overflowx',
				'name'           => __( 'Overflow-X', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable overflow-x.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'overflowy' => array(
				//'option_key'     => 'overflowy',
				'name'           => __( 'Overflow-Y', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable overflow-y.', $this->keys['plugin_domain'] ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'cursordragspeed' => array(
				//'option_key'     => 'cursordragspeed',
				'name'           => __( 'Cursor Drag Speed', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Choose the momentum on cursor drag.', $this->keys['plugin_domain'] ),
				'frontend_value' => '1.2',
				'backend_value'  => '1.2',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none'
			),
			'rtlmode' => array(
				//'option_key'     => 'rtlmode',
				'name'           => __( 'RTL-Mode', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable rtl-mode.', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'cursordragontouch' => array(
				//'option_key'     => 'cursordragontouch',
				'name'           => __( 'Cursor Drag On Touch', $this->keys['plugin_domain'] ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable cursor drag on touch.', $this->keys['plugin_domain'] ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
		);

		return $extended_options;
	}

	/**
	 * Returns all plugin options and their meta data.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $plugin_options
	 */
	private function plugin_options() {

		$plugin_options = array(
			'backtop_enabled' => array(
				//'option_key'     => 'backtop_enabled',
				'name'           => __( 'backTop', $this->keys['plugin_domain'] ),
				'settings_group' => 'plugin',
				'title'          => __( 'Activate or deactivate backTop.', $this->keys['plugin_domain'] ),
				'default_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
			'scrollto_enabled' => array(
				//'option_key'     => 'scrollto_enabled',
				'name'           => __( 'scrollTo', $this->keys['plugin_domain'] ),
				'settings_group' => 'plugin',
				'title'          => __( 'Activate or deactivate scrollTo.', $this->keys['plugin_domain'] ),
				'default_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none'
			),
		);

		return $plugin_options;
	}

	/**
	 * Writes the requested option group(s) values to the database.
	 *
     * @since  0.1.0
	 * @param  string $tab | void
	 * @return void
	 */
	public function seed_options( $tab = null ) {

		if ( isset( $tab ) ) {

			$options[ $tab ] = $this->get_default_options( $tab );

			update_option( $this->keys['option_group'], $options );
		} else {

			foreach ( $this->settings_sections as $settings_section ) {

				$options[ $settings_section ] = $this->get_default_options( $settings_section );

				ksort( $options );

				update_option( $this->keys['option_group'], $options );
			}
		}
	}

	/**
	 * Updates the database with the default values for the requested settings section.
	 *
     * @since  0.1.0
	 * @param  string $tab | void
	 * @return bool
	 */
	public function reset_settings( $tab = null ) {

		$options = [];

		if ( isset( $tab ) ) {

			$options = get_option( $this->keys['option_group'] );

			unset( $options[ $tab ] );

			$options[ $tab ] = $this->get_default_options( $tab );

			if ( false !== update_option( $this->keys['option_group'], $options ) ) {

				return true;
			} else {

				return false;
			}
		} else {

			foreach ( $this->settings_sections as $index => $tab ) {

				$options[ $tab ] = $this->get_default_options( $tab );

			}

			if ( false !== update_option( $this->keys['option_group'], $options ) ) {

				return true;
			} else {

				return true;
			}
		}
	}

	/**
	 * Retrieves the options array for the requested settings section.
	 *
	 * @param  string $settings_section
	 * @access private
	 * @return array  $settings
	 */
	private function retrieve_settings( $settings_section ) {

		$settings = null;

		if ( 'plugin' == $settings_section ) {

			$settings = $this->plugin_options();
		} else if ( 'extended' == $settings_section ) {

			$settings = $this->extended_options();
		} else {

			$settings = $this->basic_options();
		}

		return $settings;
	}

	/**
	 * Processes the basic options array and returns the (now prefixed) id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $view
	 * @return array  $basic_options
	 */
	private function get_basic_options( $view ) {

		$basic_options = [ ];

		$options = $this->basic_options();

		foreach ( $options as $option_key => $args ) {

			$basic_options[ $option_key ] = $args[ $view . '_' . 'value' ];
		}

		return $basic_options;
	}

	/**
	 * Processes the extended options array and returns the (now prefixed) id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $view
	 * @return array  $extended_options
	 */
	private function get_extended_options( $view ) {

		$extended_options = [ ];

		$options = $this->extended_options();

		foreach ( $options as $option_key => $args ) {

			$extended_options[ $option_key ] = $args[ $view . '_' . 'value' ];
		}

		return $extended_options;
	}

	/**
	 * Processes the plugin options array and returns the (now prefixed) id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $section
	 * @return array  $plugin_options
	 */
	public function get_plugin_options() {

		$plugin_options = [ ];

		$options = $this->plugin_options();

		foreach ( $options as $option_key => $args ) {

			$plugin_options[ $option_key ] = $args['default_value'];
		}

		return $plugin_options;
	}

	/**
	 * Retrieves the default options per requested section.
	 *
	 * @since  0.1.0
	 * @access private
	 * @param  string $view
	 * @return array $section_defaults
	 */
	public function get_default_options( $view ) {

		if ( 'plugin' == $view ) {

			$section_defaults = $this->get_plugin_options();
		} else {

			$section_defaults = array_merge( $this->get_basic_options( $view ), $this->get_extended_options( $view ) );
		}

		return $section_defaults;
	}

	/**
	 * Extracts the necessary meta data from the requested options array.
	 *
	 * @param  string $settings_pack
	 * @access private
	 * @return array  $args
	 */
	public function get_args( $settings_section ) {

		$options = $this->retrieve_settings( $settings_section );
		$args    = [ ];

		foreach ( $options as $option_key => $arguments ) {

			$args[ $option_key ] = array(
				'option_key'     => $option_key,
				'name'           => $arguments['name'],
				'settings_group' => $arguments['settings_group'],
				'title'          => $arguments['title'],
				'input_type'     => $arguments['input_type'],
				'select_values'  => $arguments['select_values']
			);
		}

		return $args;
	}

	/**
	 * Returns the meta data necessary for rendering the requested settings section heading.
	 *
	 * @since  0.1.0
	 * @param  string $section
	 * @return array  $heading
	 */
	public function get_section_heading( $section ) {

		$basic_settings = array(
			'settings_group' => 'basic',
			'title'          => __( 'Basic Settings', $this->keys['plugin_domain'] ),
			'callback'       => 'basic_settings_section_callback',
			'class'          => 'icon icon-equalizer'
		);

		$extended_settings = array(
			'settings_group' => 'extended',
			'title'          => __( 'Extended Settings', $this->keys['plugin_domain'] ),
			'callback'       => 'extended_settings_section_callback',
			'class'          => 'icon icon-equalizer'
		);

		$plugin_settings = array(
			'settings_group' => 'plugin',
			'title'          => __( 'Plugin Settings', $this->keys['plugin_domain'] ),
			'callback'       => 'plugin_settings_section_callback',
			'class'          => 'icon icon-equalizer'
		);

		switch ( $section ) {

			case( 'basic' == $section );
				$heading = $basic_settings;
				break;

			case( 'extended' == $section );
				$heading = $extended_settings;
				break;

			case( 'plugin' == $section );
				$heading = $plugin_settings;
				break;

			default:
				return false;
		}

		return $heading;
	}

	/**
	 * Retrieves the notice levels for the validation errors.
	 *
	 * @since  0.1.0
	 * @return array $notice_levels
	 */
	public function get_notice_levels() {

		$notice_levels = [ ];

		$array = array_merge( $this->basic_options(), $this->extended_options() );

		foreach ( $array as $key => $value ) {

			if ( 'none' !== $value['notice_level'] ) {

				$notice_levels[ $value['option_key'] ] = $value['notice_level'];
			}
		}

		return $notice_levels;
	}

	/**
	 * Retrieves the names of the sections.
	 *
	 * @since  0.1.0
	 * @return array $settings_sections
	 */
	public function get_settings_sections() {

		return $this->settings_sections;
	}

	/**
	 * Returns the basic options count (the amount of basic option settings fields), which gets localized.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/menu.js
	 * @see    admin/menu/includes/menu-localisation.php
	 * @return array $basic_options_count
	 */
	public function count_basic_settings() {

		$count = count( $this->basic_options() );

		$basic_options_count = [ 'basic_options_count' => $count ];

		return $basic_options_count;
	}

}
