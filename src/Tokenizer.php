<?php
# NOTICE OF LICENSE
#
# This source file is subject to the Open Software License (OSL 3.0)
# that is available through the world-wide-web at this URL:
# http://opensource.org/licenses/osl-3.0.php
#
# -----------------------
# @author: Iván Miranda
# @version: 1.0.0
# -----------------------
# Ejecución de eventos según la petición realizada desde el navegador
# -----------------------

namespace Sincco\Tokenizer;

final class Tokenizer extends \stdClass {

	public static function create( $datos, $horas = 3 ) {
		$datos[ 'exp' ] = time( $horas * 60 * 60 );
		return $this->Encrypt( http_build_query( $datos ) );
	}

	public static function validate( $token ) {
		$token = $this->Decrypt( $token );
		parse_str( $token, $_datos );
		if( isset( $_datos[ 'exp' ] ) ) {
			if( $_datos[ 'exp' ] > time() )
				return FALSE;
			else
				return $_datos;
		} else
			return FALSE;
	}

	public static function encrypt( $data, $key = APP_KEY ) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr( hash( 'sha256', $salt.$key.$salt ), 0, 32 );
		$iv_size = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
		$encrypted = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv ) );
		return $encrypted;
	}
	
	public static function decrypt( $data, $key = APP_KEY ) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr( hash( 'sha256', $salt . $key . $salt ), 0, 32 );
		$iv_size = mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
		$iv = mcrypt_create_iv( $iv_size, MCRYPT_RAND );
		$decrypted = mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $key, base64_decode( $data ), MCRYPT_MODE_ECB, $iv );
		return $decrypted;
	}
}