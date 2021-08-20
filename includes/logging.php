<?php

function bb_sync_write_log( $log ) {
	if ( false === WP_DEBUG ) {
		return;
	}

	if ( is_array( $log ) || is_object( $log ) ) {
		$log = print_r( $log, true );
	}

	error_log( $log );
}
