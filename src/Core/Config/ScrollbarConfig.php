<?php
/**
 * Scrollbar Configuration
 *
 * Defines the schema and default values for nicescroll scrollbar options.
 * Immutable configuration following single responsibility principle.
 *
 * @package    Nicescrollr
 * @subpackage Core\Config
 * @since      1.0.0
 */

namespace Nicescrollr\Core\Config;

use Nicescrollr\Core\Contracts\ConfigInterface;

/**
 * Class ScrollbarConfig
 *
 * @package Nicescrollr\Core\Config
 */
class ScrollbarConfig implements ConfigInterface {

	/**
	 * Configuration schema
	 *
	 * @var array
	 */
	private array $schema;

	/**
	 * Default values for frontend
	 *
	 * @var array
	 */
	private array $frontend_defaults;

	/**
	 * Default values for backend
	 *
	 * @var array
	 */
	private array $backend_defaults;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->schema = $this->buildSchema();
		$this->frontend_defaults = $this->buildDefaults( 'frontend' );
		$this->backend_defaults = $this->buildDefaults( 'backend' );
	}

	/**
	 * Get configuration value
	 *
	 * @param string $key Configuration key
	 * @param mixed $default Default value
	 * @return mixed
	 */
	public function get( string $key, $default = null ) {
		return $this->schema[ $key ] ?? $default;
	}

	/**
	 * Check if key exists
	 *
	 * @param string $key Configuration key
	 * @return bool
	 */
	public function has( string $key ): bool {
		return isset( $this->schema[ $key ] );
	}

	/**
	 * Get all configuration
	 *
	 * @return array
	 */
	public function all(): array {
		return $this->schema;
	}

	/**
	 * Get configuration schema
	 *
	 * @return array
	 */
	public function schema(): array {
		return $this->schema;
	}

	/**
	 * Get defaults for a context
	 *
	 * @param string $context 'frontend' or 'backend'
	 * @return array
	 */
	public function defaults( string $context = 'frontend' ): array {
		return $context === 'backend' ? $this->backend_defaults : $this->frontend_defaults;
	}

	/**
	 * Build configuration schema
	 *
	 * @return array
	 */
	private function buildSchema(): array {
		return [
			// Basic Settings
			'enabled' => [
				'type' => 'checkbox',
				'label' => __( 'Use Nicescroll', 'nicescrollr' ),
				'description' => __( 'Enable or disable Nicescroll.', 'nicescrollr' ),
				'group' => 'basic',
				'validation' => [ 'boolean' ],
			],
			'cursorcolor' => [
				'type' => 'color',
				'label' => __( 'Cursor Color', 'nicescrollr' ),
				'description' => __( 'Choose cursor color.', 'nicescrollr' ),
				'group' => 'basic',
				'validation' => [ 'color' ],
			],
			'cursoropacitymin' => [
				'type' => 'number',
				'label' => __( 'Cursor Opacity Minimum', 'nicescrollr' ),
				'description' => __( 'Set opacity for when the cursor is inactive.', 'nicescrollr' ),
				'group' => 'basic',
				'validation' => [ 'numeric', 'min:0', 'max:1' ],
				'step' => 0.1,
			],
			'cursoropacitymax' => [
				'type' => 'number',
				'label' => __( 'Cursor Opacity Maximum', 'nicescrollr' ),
				'description' => __( 'Set opacity for when the cursor is active.', 'nicescrollr' ),
				'group' => 'basic',
				'validation' => [ 'numeric', 'min:0', 'max:1' ],
				'step' => 0.1,
			],
			'cursorwidth' => [
				'type' => 'text',
				'label' => __( 'Cursor Width', 'nicescrollr' ),
				'description' => __( 'Set cursor width (in pixel).', 'nicescrollr' ),
				'group' => 'basic',
				'validation' => [ 'dimension' ],
			],
			'scrollspeed' => [
				'type' => 'number',
				'label' => __( 'Scroll Speed', 'nicescrollr' ),
				'description' => __( 'Set scrolling speed.', 'nicescrollr' ),
				'group' => 'basic',
				'validation' => [ 'numeric', 'min:1' ],
			],
			'autohidemode' => [
				'type' => 'select',
				'label' => __( 'Autohide Mode', 'nicescrollr' ),
				'description' => __( 'Select auto-hide mode.', 'nicescrollr' ),
				'group' => 'basic',
				'options' => [
					'enabled' => __( 'enabled', 'nicescrollr' ),
					'cursor' => 'cursor',
					'disabled' => __( 'disabled', 'nicescrollr' ),
					'leave' => 'leave',
					'hidden' => 'hidden',
					'scroll' => 'scroll',
				],
				'validation' => [ 'in:enabled,cursor,disabled,leave,hidden,scroll' ],
			],
			// Extended Settings
			'zindex' => [
				'type' => 'number',
				'label' => __( 'Z-Index', 'nicescrollr' ),
				'description' => __( 'Set the z-index value.', 'nicescrollr' ),
				'group' => 'extended',
				'validation' => [ 'numeric' ],
			],
			'railalign' => [
				'type' => 'select',
				'label' => __( 'Rail Alignment', 'nicescrollr' ),
				'description' => __( 'Horizontal alignment of the scrollbar.', 'nicescrollr' ),
				'group' => 'extended',
				'options' => [
					'left' => __( 'left', 'nicescrollr' ),
					'right' => __( 'right', 'nicescrollr' ),
				],
				'validation' => [ 'in:left,right' ],
			],
		];
	}

	/**
	 * Build default values
	 *
	 * @param string $context 'frontend' or 'backend'
	 * @return array
	 */
	private function buildDefaults( string $context ): array {
		$defaults = [
			'enabled' => true,
			'cursorcolor' => 'rgba(193, 193, 193, 1)',
			'cursoropacitymin' => '0.0',
			'cursoropacitymax' => '1.0',
			'cursorwidth' => $context === 'backend' ? '12px' : '8px',
			'scrollspeed' => 60,
			'autohidemode' => 'disabled',
			'zindex' => 999,
			'railalign' => 'right',
		];

		return $defaults;
	}
}
