<?php

namespace Neoncitylights\Base64String;

use function base64_decode;
use function explode;
use function preg_match;
use function sprintf;

/**
 * @see https://tools.ietf.org/html/rfc2397
 * @license MIT
 */
class Base64String {
	private const REGEX_URL_PREFIX = "#data:([-\w.\+]+/[-\w.\+]+);base64#";
	private const TOKEN_COMMA = ',';

	/** @var string */
	private $mimeType;

	/** @var string */
	private $base64Value;

	/** @var string */
	private $decodedValue;

	/**
	 * @param string $mimeType
	 * @param string $base64Value
	 */
	public function __construct( string $mimeType, string $base64Value ) {
		$this->mimeType = $mimeType;
		$this->base64Value = $base64Value;
	}

	/**
	 * Creates a Base64String object from a data URL.
	 *
	 * @param string $dataUrl
	 * @return self|null
	 */
	public static function newFromDataUrl( string $dataUrl ) : ?self {
		if ( strpos( $dataUrl, self::TOKEN_COMMA ) === false ) {
			return null;
		}

		list( $prefixedUrl, $base64Value ) = explode( self::TOKEN_COMMA, $dataUrl );
		$matches = [];
		preg_match( self::REGEX_URL_PREFIX, $prefixedUrl, $matches );

		if ( empty( $matches ) ) {
			return null;
		}

		return new self( $matches[1], $base64Value );
	}

	/**
	 * Gets the MIME type of the encoded base64 string.
	 *
	 * @return string
	 */
	public function getMimeType() : string {
		return $this->mimeType;
	}

	/**
	 * Gets the original, encoded base64 value.
	 * @return string
	 */
	public function getBase64Value() : string {
		return $this->base64Value;
	}

	/**
	 * Gets a decoded value of the base64 string.
	 *
	 * @return string
	 */
	public function getDecodedValue() : string {
		if ( $this->decodedValue !== null ) {
			return $this->decodedValue;
		}

		$this->decodedValue = base64_decode( $this->base64Value );
		return $this->decodedValue;
	}

	/**
	 * Checks if the MIME type matches the given MIME type passed by the user.
	 *
	 * @param string $mimeType
	 * @return bool
	 */
	public function doesMimeTypeMatch( string $mimeType ) : bool {
		return $this->mimeType === $mimeType;
	}

	/**
	 * Returns a data URL compliant with RFC 2397.
	 *
	 * @return string
	 */
	public function getDataUrl() : string {
		return sprintf( 'data:%s;base64,%s', $this->mimeType, $this->base64Value );
	}
}
