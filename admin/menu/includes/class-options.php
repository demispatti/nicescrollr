<?php

namespace Nicescrollr\Admin\Menu\Includes;

/**
 * If this file is called directly, abort.
 */
if( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The class that maintains the default options and the related meta data.
 *
 * @note              The terms 'basic' and 'extended' were created to divide
 *                    the available options into reasonable parts. That's just it.
 * @since             0.1.0
 * @package           nicescrollr
 * @subpackage        nicescrollr/admin/menu/includes
 * Author:            Demis Patti <wp@demispatti.ch>
 * Author URI:        https://demispatti.ch
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
class Nsr_Options {

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
	 *  The names of the settings tabs.
	 *
	 * @since  0.1.0
	 * @access static
	 * @var    array $settings_tabs
	 */
	public static $settings_tabs = array( 'frontend', 'backend' );

	/**
	 * Assigns the required parameters to its instance.
	 *
	 * @param string $domain
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
	private function basic_settings() {

		$basic_options = array(
			'enabled' => array(
				'name' => __( 'Use Nicescroll', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Enable or disable Nicescroll.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cursorcolor' => array(
				'name' => __( 'Cursor Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Choose cursor color.', $this->domain ),
				'frontend_value' => 'rgba(193, 193, 193, 1)',
				'backend_value' => 'rgba(193, 193, 193, 1)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'cursoropacitymin' => array(
				'name' => __( 'Cursor Opacity Minimum', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set opacity for when the cursor is inactive.', $this->domain ),
				'frontend_value' => '0.0',
				'backend_value' => '0.0',
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'cursoropacitymax' => array(
				'name' => __( 'Cursor Opacity Maximum', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set opacity for when the cursor is active.', $this->domain ),
				'frontend_value' => '1.0',
				'backend_value' => '1.0',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'cursorwidth' => array(
				'name' => __( 'Cursor Width', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set cursor width (in pixel).', $this->domain ),
				'frontend_value' => '8px',
				'backend_value' => '12px',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'cursorborderwidth' => array(
				'name' => __( 'Cursor Border Width', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set cursor border width (in pixel).', $this->domain ),
				'frontend_value' => '1px',
				'backend_value' => '1px',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'cursorborderstate' => array(
				'name' => __( 'Cursor Border State', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Select cursor border state.', $this->domain ),
				'frontend_value' => 'solid',
				'backend_value' => 'solid',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array( 'solid' => 'solid', 'dashed' => 'dashed', 'dotted' => 'dotted', 'double' => 'double', 'none' => __( 'none', $this->domain ) ),
			),
			'cursorbordercolor' => array(
				'name' => __( 'Cursor Border Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set cursor border color.', $this->domain ),
				'frontend_value' => 'rgba(255, 255, 255, 1)',
				'backend_value' => 'rgba(255, 255, 255, 1)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'cursorborderradius' => array(
				'name' => __( 'Cursor Border Radius', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set cursor border radius (in pixel).', $this->domain ),
				'frontend_value' => '8px',
				'backend_value' => '12px',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'background' => array(
				'name' => __( 'Rail Background', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Change the background color of the rail, or leave it blank.', $this->domain ),
				'frontend_value' => 'rgba(217, 217, 217, 1)',
				'backend_value' => 'rgba(217, 217, 217, 1)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'scrollspeed' => array(
				'name' => __( 'Scroll Speed', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set scrolling speed.', $this->domain ),
				'frontend_value' => 60,
				'backend_value' => 60,
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'mousescrollstep' => array(
				'name' => __( 'Mouse Scroll Step', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Set scrolling speed for the mousewheel.', $this->domain ),
				'frontend_value' => 40,
				'backend_value' => 40,
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'autohidemode' => array(
				'name' => __( 'Autohide Mode', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Select auto-hide mode.', $this->domain ),
				'frontend_value' => 'disabled',
				'backend_value' => 'disabled',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array(
					'enabled' => __( 'enabled', $this->domain ),// true
					'cursor' => 'cursor',
					'disabled' => __( 'disabled', $this->domain ),// false
					'leave' => 'leave',
					'hidden' => 'hidden',
					'scroll' => 'scroll'
				),
			),
			'default_scrollbar' => array(
				'name' => __( 'Show Default Scrollbar', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Use the browsers default scrollbar look.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'mobile_devices_enabled' => array(
				'name' => __( 'Enable On Mobile Devices', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'basic',
				'title' => __( 'Enable or disable Nicescroll on mobile devices. This will only work if Nicescroll is enabled.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
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
	private function extended_settings() {

		$extended_options = array(
			'zindex' => array(
				'name' => __( 'Z-Index', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set z-index.', $this->domain ),
				'frontend_value' => '99998',
				'backend_value' => '99998',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'emulatetouch' => array(
				'name' => __( 'Emulate Touch', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Enable cursor-drag scrolling like touch devices in desktop computer.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'hwacceleration' => array(
				'name' => __( 'Hardware Acceleration', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Switch hardware acceleration on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'boxzoom' => array(
				'name' => __( 'Box Zoom', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Enable zoom for box content.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'dblclickzoom' => array(
				'name' => __( 'Double Click Zoom', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Zoom activated when double click on box ("Box Zoom" must be "on").', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'gesturezoom' => array(
				'name' => __( 'Gesture Zoom', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Zoom on pitch in/out on box (For touch devices, set "Box Zoom" on).', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'grabcursorenabled' => array(
				'name' => __( 'Grab-Cursor', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Display "grab" icon ("Touch Behavior" must be possible and "on").', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'iframeautoresize' => array(
				'name' => __( 'iFrame Autoresize', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Switch auto-resize for iFrames on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cursorminheight' => array(
				'name' => __( 'Cursor Minimum Height', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set the minimum cursor height.', $this->domain ),
				'frontend_value' => 32,
				'backend_value' => 32,
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'preservenativescrolling' => array(
				'name' => __( 'Preserve Native Scrolling', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Scroll native scrollable areas with mouse.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'railoffset' => array(
				'name' => __( 'Rail Offset', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Add offset for rail position or disable it.', $this->domain ),
				'frontend_value' => 'false',
				'backend_value' => 'false',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array(
					'false' => __( 'off', $this->domain ),
					'top' => 'top',
					'left' => 'left',
				),
			),
			'bouncescroll' => array(
				'name' => __( 'Bounce Scroll', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Mobile-like scroll bouncing at the end of content.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'spacebarenabled' => array(
				'name' => __( 'Spacebar', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Page down-scrolling with the spacebar.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'railpaddingtop' => array(
				'name' => __( 'Rail Padding Top', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set rail padding top.', $this->domain ),
				'frontend_value' => '0',
				'backend_value' => '0',
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'railpaddingright' => array(
				'name' => __( 'Rail Padding Right', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set rail padding right.', $this->domain ),
				'frontend_value' => '0',
				'backend_value' => '0',
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'railpaddingbottom' => array(
				'name' => __( 'Rail Padding Bottom', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set rail padding bottom.', $this->domain ),
				'frontend_value' => '0',
				'backend_value' => '0',
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'railpaddingleft' => array(
				'name' => __( 'Rail Padding Left', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set rail padding left.', $this->domain ),
				'frontend_value' => 0,
				'backend_value' => 0,
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'disableoutline' => array(
				'name' => __( 'Outline', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'For chrome browser, disable outline.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'horizrailenabled' => array(
				'name' => __( 'Horizontal Rail', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Let Nicescroll manage horizontal scrolling.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'railalign' => array(
				'name' => __( 'Rail Alignment Horizontal', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Select alignment of horizontal rail.', $this->domain ),
				'frontend_value' => 'right',
				'backend_value' => 'right',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array( 'right' => __( 'right', $this->domain ), 'left' => __( 'left', $this->domain ) ),
			),
			'railvalign' => array(
				'name' => __( 'Rail Alignment Vertical', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Select alignment of vertical rail.', $this->domain ),
				'frontend_value' => 'bottom',
				'backend_value' => 'bottom',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array( 'bottom' => __( 'bottom', $this->domain ), 'top' => __( 'top', $this->domain ) ),
			),
			'enabletranslate3d' => array(
				'name' => __( 'Translate3D', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Switch translate3d on or off.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'enablemousewheel' => array(
				'name' => __( 'Mouse Wheel', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Let Nicescroll manage mousewheel events.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'enablekeyboard' => array(
				'name' => __( 'Keyboard', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Let Nicescroll manage keyboard events.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'smoothscroll' => array(
				'name' => __( 'Smooth Scroll', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Scroll with ease movement.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'sensitiverail' => array(
				'name' => __( 'Sensitive Rail', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Scrolling trough click on rail.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'enablemouselockapi' => array(
				'name' => __( 'Mouse Lock API', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => __( 'extended', $this->domain ),
				'title' => 'Use mouse caption lock API.',
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cursorfixedheight' => array(
				'name' => __( 'Cursor Fixed Height', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Select cursor fixed height or disable it.', $this->domain ),
				'frontend_value' => '160',
				'backend_value' => '160',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array( 'false' => __( 'off', $this->domain ), '60' => '60', '100' => '100', '160' => '160', '220' => '220', '280' => '280' ),
			),
			'directionlockdeadzone' => array(
				'name' => __( 'Direction Lock Dead Zone', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Choose dead zone (in pixel) for direction lock activation.', $this->domain ),
				'frontend_value' => 6,
				'backend_value' => 6,
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'hidecursordelay' => array(
				'name' => __( 'Autohide Delay', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Delay in miliseconds for fading out the scrollbar.', $this->domain ),
				'frontend_value' => 400,
				'backend_value' => 400,
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'nativeparentscrolling' => array(
				'name' => __( 'Native Parent Scrolling', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Detect bottom of content and let parent scroll, as native scroll does.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'enablescrollonselection' => array(
				'name' => __( 'Scroll On Selection', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Auto-scrolling of content while selecting text.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'cursordragspeed' => array(
				'name' => __( 'Cursor Drag Speed', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Choose the momentum on cursor drag.', $this->domain ),
				'frontend_value' => '0.3',
				'backend_value' => '0.3',
				'input_type' => 'text',
				'notice_level' => 'notice-warning',
				'select_values' => 'none',
			),
			'rtlmode' => array(
				'name' => __( 'RTL-Mode', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Enable or disable rtl-mode.', $this->domain ),
				'frontend_value' => 'auto',
				'backend_value' => 'auto',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array( 'auto' => 'auto', 'false' => __( 'off', $this->domain ) ),
			),
			'cursordragontouch' => array(
				'name' => __( 'Cursor Drag On Touch', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Enable or disable cursor drag on touch.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'oneaxismousemode' => array(
				'name' => __( 'One Axis Mouse Mode', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'It permits horizontal scrolling with mousewheel on horizontal only content, if false (vertical-only) mousewheel don\'t scroll horizontally, if value is auto detects two-axis mouse.', $this->domain ),
				'frontend_value' => 'auto',
				'backend_value' => 'auto',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array( 'auto' => 'auto', 'false' => __( 'off', $this->domain ) ),
			),
			'preventmultitouchscrolling' => array(
				'name' => __( 'Prevent Multitouch Scrolling', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Prevent scrolling on multitouch events.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'disablemutationobserver' => array(
				'name' => __( 'Disable Mutation Observer', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Force MutationObserver disabled.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => false,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'enableobserver' => array(
				'name' => __( 'Enable Observer', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Enable DOM changing observer, it tries to resize/hide/show when parent or content div had changed.', $this->domain ),
				'frontend_value' => true,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'none',
				'select_values' => 'none',
			),
			'scrollbarid' => array(
				'name' => __( 'Scrollbar ID', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'extended',
				'title' => __( 'Set a custom ID for nicescroll bars.', $this->domain ),
				'frontend_value' => 'ascrail2000',
				'backend_value' => 'ascrail2000',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
		);

		return $extended_options;
	}

	/**
	 * Returns all backtop options and their meta data.
	 *
	 * @since  0.1.0
	 * @access private
	 * @return array $plugin_options
	 */
	private function backtop_settings() {

		$plugin_options = array(

			'bt_enabled' => array(
				'name' => __( 'BackTop Button', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Enable backtop.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_mobile_enabled' => array(
				'name' => __( 'BackTop On Mobile Devices', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Enable backtop button on mobile devices.', $this->domain ),
				'frontend_value' => false,
				'backend_value' => true,
				'input_type' => 'checkbox',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_size' => array(
				'name' => __( 'Button Size', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set button size.', $this->domain ),
				'frontend_value' => 'default',
				'backend_value' => 'default',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array(
					'small' => __( 'small', $this->domain ),
					'medium' => __( 'medium', $this->domain ),
					'default' => __( 'default', $this->domain ),
					'large' => __( 'large', $this->domain )
				),
			),

			'bt_arrow_color' => array(
				'name' => __( 'Arrow Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set arrow color.', $this->domain ),
				'frontend_value' => 'rgba(0, 0, 0, 1.0)',
				'backend_value' => 'rgba(0, 0, 0, 1.0)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_arrow_hover_color' => array(
				'name' => __( 'Arrow Hover Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set arrow hover color.', $this->domain ),
				'frontend_value' => 'rgba(255, 255, 255, 1.0)',
				'backend_value' => 'rgba(255, 255, 255, 1.0)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),

			'bt_background_color' => array(
				'name' => __( 'Background Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set background color.', $this->domain ),
				'frontend_value' => 'rgba(0, 0, 0, 0.12)',
				'backend_value' => 'rgba(0, 0, 0, 0.12)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_hover_background_color' => array(
				'name' => __( 'Hover Background Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set background color.', $this->domain ),
				'frontend_value' => 'rgba(0, 0, 0, 0.60)',
				'backend_value' => 'rgba(0, 0, 0, 0.60)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),

			'bt_border_color' => array(
				'name' => __( 'Border Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border color.', $this->domain ),
				'frontend_value' => 'rgba(0, 0, 0, 0)',
				'backend_value' => 'rgba(0, 0, 0, 0)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_hover_border_color' => array(
				'name' => __( 'Hover Border Color', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border color.', $this->domain ),
				'frontend_value' => 'rgba(0, 0, 0, 0)',
				'backend_value' => 'rgba(0, 0, 0, 0)',
				'input_type' => 'color',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_border_width' => array(
				'name' => __( 'Border Width', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border width in pixels.', $this->domain ),
				'frontend_value' => '1px',
				'backend_value' => '1px',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_border_style' => array(
				'name' => __( 'Border Style', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border style.', $this->domain ),
				'frontend_value' => 'solid',
				'backend_value' => 'solid',
				'input_type' => 'select',
				'notice_level' => 'none',
				'select_values' => array(
					'solid' => 'solid',
					'dotted' => 'dotted',
					'dashed' => 'dashed',
					'double' => 'double',
					'groove' => 'groove',
					'ridge' => 'ridge',
					'inset' => 'inset',
					'outset' => 'outset',
					'initial' => 'initial',
					'inherit' => 'inherit',
					'none' => __( 'none', $this->domain ),
					'hidden' => 'hidden'
				),
			),

			'bt_posx_from_right' => array(
				'name' => __( 'Position From Right', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set button position from right in pixels.', $this->domain ),
				'frontend_value' => '32px',
				'backend_value' => '32px',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_posy_from_bottom' => array(
				'name' => __( 'Position From Bottom', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set button position from bottom in pixels.', $this->domain ),
				'frontend_value' => '32px',
				'backend_value' => '32px',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),

			'bt_border_radius_top_left' => array(
				'name' => __( 'Border Radius Top Left', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border radius top left in pixels or percent.', $this->domain ),
				'frontend_value' => '50%',
				'backend_value' => '50%',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_border_radius_top_right' => array(
				'name' => __( 'Border Radius Top Right', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border radius top right in pixels or percent.', $this->domain ),
				'frontend_value' => '50%',
				'backend_value' => '50%',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_border_radius_bottom_left' => array(
				'name' => __( 'Border Radius Bottom Left', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border radius bottom left in pixels or percent.', $this->domain ),
				'frontend_value' => '50%',
				'backend_value' => '50%',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),
			'bt_border_radius_bottom_right' => array(
				'name' => __( 'Border Radius Bottom Right', $this->domain ),
				'callback' => 'render_settings_field_callback',
				'settings_group' => 'backtop',
				'title' => __( 'Set border radius bottom right in pixels or percent.', $this->domain ),
				'frontend_value' => '50%',
				'backend_value' => '50%',
				'input_type' => 'text',
				'notice_level' => 'notice-correction',
				'select_values' => 'none',
			),

		);

		return $plugin_options;
	}

	/**
	 * Writes the requested option group(s) values to the database.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $settings_tab | null
	 *
	 * @return void
	 */
	public function seed_options( $settings_tab = null ) {

		if( null !== $settings_tab ) {

			$options = get_option( 'nicescrollr_options' );

			if( isset( $options[$settings_tab] ) ) {

				unset( $options[$settings_tab] );
			}

			$options[$settings_tab] = $this->get_default_settings( $settings_tab );

			update_option( 'nicescrollr_options', $options, true );
		}
		else {

			foreach( self::$settings_tabs as $settings_section ) {

				$options[$settings_section] = $this->get_default_settings( $settings_section );

				ksort( $options );

				update_option( 'nicescrollr_options', $options, true );
			}
			// Maybe add the version number on "reset all".
			if( ! isset( $options['nicescrollr']['version'] ) ) {

				$options = get_option( 'nicescrollr_options' );
				$options['nicescrollr']['version'] = '0.5.6';

				update_option( 'nicescrollr_options', $options, true );
			}
		}
	}

	/**
	 * Updates the database with the default values for the requested settings section.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $settings_tab | null
	 *
	 * @return bool|\WP_Error
	 */
	public function reset_settings( $settings_tab ) {

		$options = get_option( 'nicescrollr_options' );

		if( false !== $options || ! empty( $options ) ) {

			unset( $options[$settings_tab] );

			$options[$settings_tab] = $this->get_default_settings( $settings_tab );

			delete_option( 'nicescrollr_options' );
			$result = add_option( 'nicescrollr_options', $options, true );

			if( true === $result ) {

				return true;
			}
		}

		return new \WP_Error( - 1, __( 'Failed resetting the settings. Please refresh the page and try again.', $this->domain ) );
	}

	/**
	 * Retrieves the options array for the requested settings section.
	 *
	 * @param  string $settings_section
	 *
	 * @access public
	 * @return mixed array $settings / bool false
	 */
	public function get_settings_per_section( $settings_section ) {

		$settings = null;

		if( 'basic' === $settings_section ) {
			return $this->basic_settings();
		}

		if( 'extended' === $settings_section ) {
			return $this->extended_settings();
		}
		if( 'backtop' === $settings_section ) {
			return $this->backtop_settings();
		}

		return false;
	}

	/**
	 * Processes the basic options array and returns the id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  string $view
	 *
	 * @return array  $basic_options
	 */
	private function get_basic_settings( $view ) {

		$basic_options = array();

		$options = $this->basic_settings();

		foreach( $options as $option_key => $args ) {

			$basic_options[$option_key] = $args[$view . '_' . 'value'];
		}

		return $basic_options;
	}

	/**
	 * Processes the extended options array and returns the id/value pair.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  string $view
	 *
	 * @return array  $extended_options
	 */
	private function get_extended_settings( $view ) {

		$extended_options = array();

		$options = $this->extended_settings();

		foreach( $options as $option_key => $args ) {

			$extended_options[$option_key] = $args[$view . '_' . 'value'];
		}

		return $extended_options;
	}

	/**
	 * Returns an array containing the options for the backTop button.
	 *
	 * @param $view
	 *
	 * @return array
	 */
	public function get_backtop_settings( $view ) {

		$backtop_options = array();

		$options = $this->backtop_settings();

		foreach( $options as $option_key => $args ) {

			$backtop_options[$option_key] = $args[$view . '_' . 'value'];
		}

		return $backtop_options;
	}

	/**
	 * Retrieves the default options per requested section.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  bool false|string $view
	 *
	 * @return mixed
	 */
	public function get_options( $view = false ) {

		if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'heartbeat' ) {
			die();
		}

		$options = get_option( 'nicescrollr_options' );

		if( false === $options ) {
			$this->seed_options();
		}

		if( false === $view ) {
			return get_option( 'nicescrollr_options' );
		}

		$stored_options = get_option( 'nicescrollr_options' );
		if( ! isset( $stored_options[$view] ) ) {

			return $this->get_default_settings( $view );
		}

		return $stored_options[$view];
	}

	/**
	 * Retrieves the default options per requested section.
	 *
	 * @since  0.1.0
	 * @access private
	 *
	 * @param  string $view
	 *
	 * @return array $section_defaults
	 */
	public function get_default_settings( $view ) {

		return array_merge( $this->get_basic_settings( $view ), $this->get_extended_settings( $view ), $this->get_backtop_settings( $view ) );
	}

	/**
	 * Extracts the necessary meta data from the requested options array.
	 *
	 * @param  string $settings_section
	 *
	 * @access private
	 * @return array  $args
	 */
	public function get_args( $settings_section = 'basic' ) {

		$options = (array) $this->get_settings_per_section( $settings_section );
		$args = array();

		foreach( $options as $option_key => $arguments ) {

			$args[$option_key] = array(
				'option_key' => $option_key,
				'name' => $arguments['name'],
				'settings_group' => $arguments['settings_group'],
				'title' => $arguments['title'],
				'input_type' => $arguments['input_type'],
				'select_values' => $arguments['select_values'],
				'callback' => $arguments['callback']
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
			'title' => '<h2 class="settings-toggle basic upper nicescrollr_settings_toggle"><span class="dashicons dashicons-menu"></span>' . __( 'Basic Settings', $this->domain ) . '</h2>',
			'callback' => 'basic_settings_section_callback',
			'class' => 'fa fa-sliders',
		);

		$extended_settings = array(
			'settings_group' => 'extended',
			'title' => '<h2 class="settings-toggle advanced lower nicescrollr_settings_toggle"><span class="dashicons dashicons-menu"></span>' . __( 'Extended Settings', $this->domain ) . '</h2>',
			'callback' => 'extended_settings_section_callback',
			'class' => 'fa fa-sliders',
		);

		$backtop_settings = array(
			'settings_group' => 'backtop',
			'title' => '<h2 class="settings-toggle backtop nicescrollr_settings_toggle"><span class="dashicons dashicons-menu"></span>' . __( 'BackTop Settings', $this->domain ) . '</h2>',
			'callback' => 'backtop_settings_section_callback',
			'class' => 'fa fa-sliders',
		);

		switch( $section ) {

			case( 'basic' === $section );

				$heading = $basic_settings;
				break;

			case( 'extended' === $section );

				$heading = $extended_settings;
				break;

			case( 'backtop' === $section );

				$heading = $backtop_settings;
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

		$array = array_merge( $this->basic_settings(), $this->extended_settings(), $this->backtop_settings() );

		foreach( $array as $key => $value ) {

			if( 'none' !== $value['notice_level'] ) {

				$notice_levels[$key] = $value['notice_level'];
			}
		}

		return $notice_levels;
	}

	/**
	 * Retrieves the names of the sections.
	 *
	 * @since  0.1.0
	 * @return array $settings_tabs
	 */
	public function get_settings_tabs() {

		return self::$settings_tabs;
	}

	/**
	 * Returns the basic options count (the amount of basic option settings fields), which gets localized.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/menu.js
	 * @see    admin/menu/includes/menu-localisation.php
	 * @return array
	 */
	public function count_basic_settings() {

		$count = count( $this->basic_settings() );

		return array( 'basic_options_count' => $count );
	}

	/**
	 * Returns the basic options count (the amount of basic option settings fields), which gets localized.
	 *
	 * @since  0.1.0
	 * @see    admin/menu/js/menu.js
	 * @see    admin/menu/includes/menu-localisation.php
	 * @return array
	 */
	public function count_extended_settings() {

		$count = count( $this->extended_settings() );

		return array( 'extended_options_count' => $count );
	}

	/**
	 * Returns the meta data related to the options.
	 *
	 * @return array
	 */
	public function get_options_meta() {

		return array_merge( $this->basic_settings(), $this->extended_settings(), $this->backtop_settings() );
	}

}
