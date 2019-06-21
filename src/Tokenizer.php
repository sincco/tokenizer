<?php
# NOTICE OF LICENSE
#
# This source file is subject to the Open Software License (OSL 3.0)
# that is available through the world-wide-web at this URL:
# http://opensource.org/licenses/osl-3.0.php
#
# -----------------------
# @author: Iván Miranda
# @version: 1.1.1
# -----------------------
# Create & validate a token string for user's data
# -----------------------

namespace Sincco\Tools;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

final class Tokenizer extends \stdClass {

	private static $instance;
	// Default values (allows to use functions in simple form)
	private $password = '66Oetf#kI6U4wYH';

	/**
	 * Creates a token string
	 * @param  array  $data     User's data that will be parsed on string
	 * @param  string  $password String password for encryption (it must be same on validation side)
	 * @param  integer $duration Duration time in minutes
	 * @return string            Token string
	 */
	public static function create( $data, $password = '', $duration = 3 ) {
		if(! self::$instance instanceof self )
			self::$instance = new self();
		if( trim( $password ) == '' )
			$password = self::$instance->password;
		$data[ 'exp' ] = time() + ( $duration * 60 );
		return self::$instance->Encrypt( http_build_query( $data ), $password );
	}

	/**
	 * Validates a token string
	 * @param  string $token    Token to validate
	 * @param  string $password String password for decrypt data
	 * @return mixed           Returns array with data if token is valid or FALSE if not
	 */
	public static function validate( $token, $password = '' ) {
		try {
			if(! self::$instance instanceof self )
				self::$instance = new self();
			if( trim( $password ) == '' )
				$password = self::$instance->password;
			$token = self::$instance->Decrypt( $token, $password );
			parse_str( $token, $data );
			if( isset( $data[ 'exp' ] ) ) {
				if( intval( time() ) <= intval( $data[ 'exp' ] ) )
					return $data;
				else
					return false;
			} else
				return false;
		} catch (\Exception $err) {
			return false;
		}
	}

	/**
	 * Encrypt data
	 */
	private static function encrypt( $data, $key ) {
		$objKey = Key::loadFromAsciiSafeString($key);
		return Crypto::encrypt($data, $objKey);
	}

	/**
	 * Decrypt data
	 */
	private static function decrypt( $data, $key ) {
		$objKey = Key::loadFromAsciiSafeString($key);
		return Crypto::decrypt($data, $objKey);
	}

	/**
	 * Clean data replacing URL characters, allowing using token as URL param
	 */
	private function cleanData( $data, $decrypt = false ) {
		$dirty = array("+", "/", "=");
		$clean = array("_PLS_", "_SLH_", "_EQL_");
		if( $decrypt )
			$response = str_replace($clean, $dirty, $data);
		else
			$response = str_replace($dirty, $clean, $data);
		return $response;
	}
}
