<?php

namespace Neoncitylights\DataUrl;

use Neoncitylights\MediaType\MediaType;
use function base64_decode;
use function sprintf;
use function strlen;
use function strrchr;
use function strrpos;
use function substr;

/**
 * @see https://tools.ietf.org/html/rfc2397
 * @license MIT
 */
class DataUrl {
	private const TOKEN_COLON = ';';
	private const TOKEN_DATA_SCHEME = 'data:';
	private const TOKEN_BASE64_EXT = ';base64,';

	/** @var MediaType */
	private $mediaType;

	/** @var string */
	private $data;

	/** @var string */
	private $decodedValue;

	/**
	 * @param MediaType $mediaType
	 * @param string $data
	 */
	public function __construct( MediaType $mediaType, string $data ) {
		$this->mediaType = $mediaType;
		$this->data = $data;
	}

	/**
	 * Creates a Base64String object from a data URL.
	 *
	 * @param string $dataUrl
	 * @return self|null
	 */
	public static function newFromString( string $dataUrl ) : ?self {
		$stringFromLastColonToken = strrchr( $dataUrl, self::TOKEN_COLON );
		$stringBeforeBase64 = substr( $dataUrl, 0, strrpos( $dataUrl, self::TOKEN_COLON ) );

		$mediaType = substr( $stringBeforeBase64, strlen( self::TOKEN_DATA_SCHEME ) );
		$data = substr( $stringFromLastColonToken, strlen( self::TOKEN_BASE64_EXT ) );

		return new self( MediaType::newFromString( $mediaType ), $data );
	}

	/**
	 * Gets the media type of the encoded base64 string.
	 *
	 * @return MediaType
	 */
	public function getMediaType() : MediaType {
		return $this->mediaType;
	}

	/**
	 * Gets the original, encoded base64 string.
	 *
	 * @return string
	 */
	public function getData() : string {
		return $this->data;
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

		$this->decodedValue = base64_decode( $this->data );
		return $this->decodedValue;
	}

	/**
	 * Returns a data URL compliant with RFC 2397.
	 *
	 * @return string
	 */
	public function __toString() : string {
		return sprintf(
			'%s%s%s%s',
			self::TOKEN_DATA_SCHEME,
			(string)$this->mediaType,
			self::TOKEN_BASE64_EXT,
			$this->data
		);
	}
}
