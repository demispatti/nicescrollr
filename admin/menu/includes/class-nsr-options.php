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
	 * The domain of the plugin.
	 *
	 * @since  0.1.0
	 *
	 * @access private
	 *
	 * @var string $domain
	 */
	private $domain;

	/**
	 *  The names of the sections.
	 *
	 * @since  0.1.0
	 * @access private
	 * @var    array $settings_sections
	 */
	private $settings_sections = array( 'frontend', 'backend', 'plugin' );

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @param array $domain
	 */
	public function __construct( $domain ) {

		$this->domain = $domain;
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
			'enabled'            => array(
				'name'           => __( 'Nicescroll', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Enable or disable Nicescroll.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'defaultScrollbar'   => array(
				'name'           => __( 'Default Scrollbar', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Use the browsers default scrollbar instead of the customizable one.', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'cursorcolor'        => array(
				'name'           => __( 'Cursor Color', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Choose cursor color.', $this->domain ),
				'frontend_value' => '#a3a3a3',
				'backend_value'  => '#5c5c5c',
				'input_type'     => 'color',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'cursoropacitymin'   => array(
				'name'           => __( 'Cursor Opacity Minimum', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set opacity for when the cursor is inactive.', $this->domain ),
				'frontend_value' => '0.0',
				'backend_value'  => '0.0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'cursoropacitymax'   => array(
				'name'           => __( 'Cursor Opacity Maximum', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set opacity for when the cursor is active.', $this->domain ),
				'frontend_value' => '0.8',
				'backend_value'  => '1.0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'cursorwidth'        => array(
				'name'           => __( 'Cursor Width', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor width (in pixel).', $this->domain ),
				'frontend_value' => '4px',
				'backend_value'  => '16px',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'cursorborderwidth'  => array(
				'name'           => __( 'Cursor Border Width', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor border width (in pixel).', $this->domain ),
				'frontend_value' => '1px',
				'backend_value'  => '2px',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'cursorborderstate'  => array(
				'name'           => __( 'Cursor Border State', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Select cursor border state.', $this->domain ),
				'frontend_value' => 'solid',
				'backend_value'  => 'solid',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( 'solid', 'dashed', 'dotted', 'double', __( 'none', $this->domain ) ),
			),
			'cursorbordercolor'  => array(
				'name'           => __( 'Cursor Border Color', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor border color.', $this->domain ),
				'frontend_value' => '#fff',
				'backend_value'  => '#fff',
				'input_type'     => 'color',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'cursorborderradius' => array(
				'name'           => __( 'Cursor Border Radius', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Set cursor border radius (in pixel).', $this->domain ),
				'frontend_value' => '12px',
				'backend_value'  => '12px',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'background'         => array(
				'name'           => __( 'Rail Background', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Change the background color of the rail, or leave it blank.', $this->domain ),
				'frontend_value' => '#f7f7f7',
				'backend_value'  => '#efefef',
				'input_type'     => 'color',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'autohidemode'       => array(
				'name'           => __( 'Autohide Mode', $this->domain ),
				'callback'       => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title'          => __( 'Select auto-hide mode.', $this->domain ),
				'frontend_value' => 'on',
				'backend_value'  => 'off',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array(
					__( 'off', $this->domain ),
					__( 'on', $this->domain ),
					__( 'cursor', $this->domain ),
				),
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
			'zindex'                  => array(
				'name'           => __( 'Z-Index', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set z-index.', $this->domain ),
				'frontend_value' => '9999',
				'backend_value'  => '9999',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'scrollspeed'             => array(
				'name'           => __( 'Scroll Speed', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set scrolling speed.', $this->domain ),
				'frontend_value' => '120',
				'backend_value'  => '120',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'mousescrollstep'         => array(
				'name'           => __( 'Mouse Scroll Step', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set scrolling speed for the mousewheel.', $this->domain ),
				'frontend_value' => '20',
				'backend_value'  => '20',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'touchbehavior'           => array(
				'name'           => __( 'Touch Behaviour', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable cursor-drag scrolling for touch-enabled computers.', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'hwacceleration'          => array(
				'name'           => __( 'Hardware Acceleration', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch hardware acceleration on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'usetransition'           => array(
				'name'           => __( 'Transitions', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch transitions on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'boxzoom'                 => array(
				'name'           => __( 'Box Zoom', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable zoom for box content.', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'dblclickzoom'            => array(
				'name'           => __( 'Double Click Zoom', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Zoom activated when double click on box ("Box Zoom" must be "on").', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'gesturezoom'             => array(
				'name'           => __( 'Gesture Zoom', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Zoom on pitch in/out on box (For touch devices, set "Box Zoom" on).', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'grabcursorenabled'       => array(
				'name'           => __( 'Grab-Cursor', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Display "grab" icon ("Touch Behavior" must be possible and "on").', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			/*'background' => array(
				'name'             => __( 'Rail Background', $this->domain ),
				'settings_group' => 'extended',
				'title'      => __( 'Change the background color of the rail, or leave it blank.', $this->domain ),
				'frontend_value'   => '',
				'backend_value'    => '#efefef',
				'input_type'       => 'color',
				'notice_level'     => 'notice-correction',
				'select_values'    => 'none'
			),*/
			'iframeautoresize'        => array(
				'name'           => __( 'iFrame Autoresize', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch auto-resize for iFrames on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'cursorminheight'         => array(
				'name'           => __( 'Cursor Minimum Height', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set the minimum cursor height.', $this->domain ),
				'frontend_value' => '32',
				'backend_value'  => '32',
				'input_type'     => 'text',
				'notice_level'   => 'notice-correction',
				'select_values'  => 'none',
			),
			'preservenativescrolling' => array(
				'name'           => __( 'Preserve Native Scrolling', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Scroll native scrollable areas with mouse.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'railoffset'              => array(
				'name'           => __( 'Rail Offset', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Add offset for rail position or disable it.', $this->domain ),
				'frontend_value' => 'off',
				'backend_value'  => 'off',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array(
					__( 'off', $this->domain ),
					__( 'top', $this->domain ),
					__( 'left', $this->domain ),
				),
			),
			'bouncescroll'            => array(
				'name'           => __( 'Bounce Scroll', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Mobile-like scroll bouncing at the end of content.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'spacebar'                => array(
				'name'           => __( 'Spacebar', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Page down-scrolling with the spacebar.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'railpaddingtop'          => array(
				'name'           => __( 'Rail Padding Top', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding top.', $this->domain ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'railpaddingright'        => array(
				'name'           => __( 'Rail Padding Right', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding right.', $this->domain ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'railpaddingbottom'       => array(
				'name'           => __( 'Rail Padding Bottom', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding bottom.', $this->domain ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'railpaddingleft'         => array(
				'name'           => __( 'Rail Padding Left', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Set rail padding left.', $this->domain ),
				'frontend_value' => '0',
				'backend_value'  => '0',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'disableoutline'          => array(
				'name'           => __( 'Outline', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'For chrome browser, disable outline.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'horizrailenabled'        => array(
				'name'           => __( 'Horizontal Rail', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Let Nicescroll manage horizontal scrolling.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'railalign'               => array(
				'name'           => __( 'Rail Alignment Horizontal', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Select alignment of horizontal rail.', $this->domain ),
				'frontend_value' => 'right',
				'backend_value'  => 'right',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( __( 'right', $this->domain ), __( 'left', $this->domain ) ),
			),
			'railvalign'              => array(
				'name'           => __( 'Rail Alignment Vertical', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Select alignment of vertical rail.', $this->domain ),
				'frontend_value' => 'bottom',
				'backend_value'  => 'bottom',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( __( 'bottom', $this->domain ), __( 'top', $this->domain ) ),
			),
			'enabletranslate3d'       => array(
				'name'           => __( 'Translate3D', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Switch translate3d on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'enablemousewheel'        => array(
				'name'           => __( 'Mouse Wheel', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Let Nicescroll manage mousewheel events.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'enablekeyboard'          => array(
				'name'           => __( 'Keyboard', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Let Nicescroll manage keyboard events.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'smoothscroll'            => array(
				'name'           => __( 'Smooth Scroll', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Scroll with ease movement.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'sensitiverail'           => array(
				'name'           => __( 'Sensitive Rail', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Scrolling trough click on rail.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'enablemouselockapi'      => array(
				'name'           => __( 'Mouse Lock API', $this->domain ),
				'settings_group' => __( 'extended', $this->domain ),
				'title'          => 'Use mouse caption lock API.',
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			/*'cursormaxheight' => array(
				'name'             => __( 'Cursor Maximum Height', $this->domain ),
				'settings_group' => 'extended',
				'title'      => __( 'Switch cursor maximum height on or off.', $this->domain ),
				'frontend_value'   => false,
				'backend_value'    => false,
				'input_type' => 'checkbox',
				'notice_level'     => 'none',
				'select_values'    => 'none'
			),*/
			'cursorfixedheight'       => array(
				'name'           => __( 'Cursor Fixed Height', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Select cursor fixed height or disable it.', $this->domain ),
				'frontend_value' => '160',
				'backend_value'  => '160',
				'input_type'     => 'select',
				'notice_level'   => 'none',
				'select_values'  => array( __( 'off', $this->domain ), '60', '100', '160', '220', '280' ),
			),
			'directionlockdeadzone'   => array(
				'name'           => __( 'Direction Lock Dead Zone', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Choose dead zone (in pixel) for direction lock activation.', $this->domain ),
				'frontend_value' => '6px',
				'backend_value'  => '6px',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'hidecursordelay'         => array(
				'name'           => __( 'Autohide Delay', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Delay in miliseconds for fading out the scrollbar.', $this->domain ),
				'frontend_value' => '400',
				'backend_value'  => '400',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'nativeparentscrolling'   => array(
				'name'           => __( 'Native Parent Scrolling', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Detect bottom of content and let parent scroll, as native scroll does.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'enablescrollonselection' => array(
				'name'           => __( 'Scroll On Selection', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Auto-scrolling of content while selecting text.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'overflowx'               => array(
				'name'           => __( 'Overflow-X', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable overflow-x.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'overflowy'               => array(
				'name'           => __( 'Overflow-Y', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable overflow-y.', $this->domain ),
				'frontend_value' => true,
				'backend_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'cursordragspeed'         => array(
				'name'           => __( 'Cursor Drag Speed', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Choose the momentum on cursor drag.', $this->domain ),
				'frontend_value' => '0.5',
				'backend_value'  => '0.5',
				'input_type'     => 'text',
				'notice_level'   => 'notice-warning',
				'select_values'  => 'none',
			),
			'rtlmode'                 => array(
				'name'           => __( 'RTL-Mode', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable rtl-mode.', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'cursordragontouch'       => array(
				'name'           => __( 'Cursor Drag On Touch', $this->domain ),
				'settings_group' => 'extended',
				'title'          => __( 'Enable or disable cursor drag on touch.', $this->domain ),
				'frontend_value' => false,
				'backend_value'  => false,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
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
			'backtop_enabled'  => array(
				'name'           => __( 'backTop', $this->domain ),
				'settings_group' => 'plugin',
				'title'          => __( 'Activate or deactivate backTop.', $this->domain ),
				'default_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
			),
			'scrollto_enabled' => array(
				'name'           => __( 'scrollTo', $this->domain ),
				'settings_group' => 'plugin',
				'title'          => __( 'Activate or deactivate scrollTo.', $this->domain ),
				'default_value'  => true,
				'input_type'     => 'checkbox',
				'notice_level'   => 'none',
				'select_values'  => 'none',
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
	public function seed_options( $tab = NULL ) {

		if( isset($tab) ) {

			$options = get_option( 'nicescrollr_options' );

			if( isset($options[ $tab ]) ) {

				unset($options[ $tab ]);
			}

			$options[ $tab ] = $this->get_default_options( $tab );

			update_option( 'nicescrollr_options', $options );
		} else {

			foreach( $this->settings_sections as $settings_section ) {

				$options[ $settings_section ] = $this->get_default_options( $settings_section );

				ksort( $options );

				update_option( 'nicescrollr_options', $options );
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
	public function reset_settings( $tab = NULL ) {

		$options = array();

		if( isset($tab) ) {

			$options = get_option( 'nicescrollr_options' );

			unset($options[ $tab ]);

			$options[ $tab ] = $this->get_default_options( $tab );

			if( false !== update_option( 'nicescrollr_options', $options ) ) {

				return true;
			} else {

				return false;
			}
		} else {

			foreach( $this->settings_sections as $index => $tab ) {

				$options[ $tab ] = $this->get_default_options( $tab );
			}

			if( false !== update_option( 'nicescrollr_options', $options ) ) {

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

		$settings = NULL;

		if( 'plugin' == $settings_section ) {

			$settings = $this->plugin_options();
		} else if( 'extended' == $settings_section ) {

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

		$basic_options = array();

		$options = $this->basic_options();

		foreach( $options as $option_key => $args ) {

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

		$extended_options = array();

		$options = $this->extended_options();

		foreach( $options as $option_key => $args ) {

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

		$plugin_options = array();

		$options = $this->plugin_options();

		foreach( $options as $option_key => $args ) {

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

		if( 'plugin' == $view ) {

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
		$args = array();

		foreach( $options as $option_key => $arguments ) {

			$args[ $option_key ] = array(
				'option_key'     => $option_key,
				'name'           => $arguments['name'],
				'settings_group' => $arguments['settings_group'],
				'title'          => $arguments['title'],
				'input_type'     => $arguments['input_type'],
				'select_values'  => $arguments['select_values'],
			);
		}

		return $args;
	}

	/**
	 * Returns the meta data necessary for rendering the requested settings section heading.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $section
	 *
     * @return mixed
	 */
	public function get_section_heading( $section ) {

		$basic_settings = array(
			'settings_group' => 'basic',
			'title'          => __( 'Basic Settings', $this->domain ),
			'callback'       => 'basic_settings_section_callback',
			'class'          => 'icon icon-equalizer',
		);

		$extended_settings = array(
			'settings_group' => 'extended',
			'title'          => __( 'Extended Settings', $this->domain ),
			'callback'       => 'extended_settings_section_callback',
			'class'          => 'icon icon-equalizer',
		);

		$plugin_settings = array(
			'settings_group' => 'plugin',
			'title'          => __( 'Plugin Settings', $this->domain ),
			'callback'       => 'plugin_settings_section_callback',
			'class'          => 'icon icon-equalizer',
		);

		switch( $section ) {

			case('basic' == $section);
				$heading = $basic_settings;
				break;

			case('extended' == $section);
				$heading = $extended_settings;
				break;

			case('plugin' == $section);
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

		$notice_levels = array();

		$array = array_merge( $this->basic_options(), $this->extended_options() );

		foreach( $array as $key => $value ) {

			if( 'none' !== $value['notice_level'] ) {

				$notice_levels[ $key ] = $value['notice_level'];
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

		$basic_options_count = array('basic_options_count' => $count);

		return $basic_options_count;
	}

}
