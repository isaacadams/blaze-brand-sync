<?php
class BrandsBlazeClient {
	//https://apidocs.blaze.me/blaze-developer-api/reference/blaze-partner-store--inventory/get-all-brands

	public function get_all_brands() {
		$data = $this->request( 'store/inventory/brands' );
		return $data->values;
	}

	public function get_all_products() {
		$data = $this->request( 'store/inventory/products' );
		return $data->values;
	}

	private function request( $endpoint, $method = 'GET', $args = array() ) {
		$blaze_domain = get_option( 'Blaze_api_domain' );
		$url          = "{$blaze_domain}/api/v1/{$endpoint}";

		$apikey = get_option( 'Blaze_api_key' );

		$response = wp_remote_request(
			$url,
			array(
				'method'  => $method,
				'headers' => array(
					'api_key' => $apikey,
				),
				'body'    => $args,
			)
		);

		if ( is_wp_error( $response ) ) {
			Logger::instance()->log( '---error when requesting ' . $endpoint . '---' . "\n" . $response . "\n" . '---end---' );
		}

		$body = json_decode( wp_remote_retrieve_body( $response ) );
		return $body;
	}
}
