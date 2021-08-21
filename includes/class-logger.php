<?php
class Logger {
	public string $date;
	public string $path_to_file;

	public function __construct( string $home_path ) {
		$this->date         = gmdate( 'd.m.Y h:i:s' );
		$this->path_to_file = $home_path . 'logs/[' . $this->date . ']_debug.log';

		if ( ! file_exists( $home_path . 'logs' ) ) {
			mkdir( $home_path . 'logs', 0777, true );
		}
	}

	public function log( string $log ) {
		if ( false === WP_DEBUG ) {
			return;
		}

		if ( is_array( $log ) || is_object( $log ) ) {
			$log = print_r( $log, true );
		}

		define( 'WP_DEBUG_LOG', $this->path_to_file );
		error_log( "{$log}\n", 3, $this->path_to_file );
		define( 'WP_DEBUG_LOG', true );
	}

	public function initial_log() {
		$this->log( '[' . $this->date . '] running blaze_brands_sync' );
	}


	private static $instance;

	public static function instance(): Logger {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Logger( BLAZE_BRAND_SYNC_HOME );
		}

		return self::$instance;
	}
}
