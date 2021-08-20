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

function bb_is_string_empty( ?string $value ) {
	return ! ( isset( $value ) && ( strlen( trim( $value ) ) > 0 ) );
}
