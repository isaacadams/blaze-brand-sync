<?php
function bb_sync_write_log( string $log ) {
	Logger::instance()->log( $log );
}

function bb_is_string_empty( ?string $value ) {
	return ! ( isset( $value ) && ( strlen( trim( $value ) ) > 0 ) );
}
