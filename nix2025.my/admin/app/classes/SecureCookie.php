<?php
class SecureCookie extends \Overclokk\Cookie\Cookie {
	                                                   // private $key    = 'твой_секретный_ключ';
	private $key = '1234567890abcdef1234567890abcdef'; // ровно 32 символа

	private $cipher = 'AES-256-CBC';

	private function iv() {
		return substr( hash( 'sha256', $this->key ), 0, 16 );
	}

	private function sign( $data ) {
		return hash_hmac( 'sha256', $data, $this->key );
	}

	private function verify(
		$data,
		$signature
	) {
		return hash_equals( $signature, $this->sign( $data ) );
	}

	public function setSecure(
		$name,
		$value,
		$expire = 3600
	) {
		$json      = json_encode( $value );
		$encrypted = openssl_encrypt( $json, $this->cipher, $this->key, 0, $this->iv() );
		$signed    = base64_encode( json_encode( [
			'data' => $encrypted,
			'sig'  => $this->sign( $encrypted ),
		] ) );

		return parent::set( $name, $signed, $expire, '/', null, true, true );
	}

	public function getSecure( $name ) {
		$raw = parent::get( $name );
		if ( !$raw ) {
			return null;
		}

		$decoded = json_decode( base64_decode( $raw ), true );
		if ( !is_array( $decoded ) || !isset( $decoded['data'], $decoded['sig'] ) ) {
			return null;
		}

		if ( !$this->verify( $decoded['data'], $decoded['sig'] ) ) {
			return null;
		}

		$decrypted = openssl_decrypt( $decoded['data'], $this->cipher, $this->key, 0, $this->iv() );

		return json_decode( $decrypted, true );
	}
}