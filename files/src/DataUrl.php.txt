<?php

namespace Neoncitylights\DataUrl;

use Neoncitylights\MediaType\MediaType;
use function base64_decode;
use function sprintf;

/**
 * @see https://tools.ietf.org/html/rfc2397
 * @license MIT
 */
class DataUrl implements DataUrlToken {
	/** @var MediaType */
	private $mediaType;

	/** @var string */
	private $data;

	/**
	 * @param MediaType $mediaType
	 * @param string $data
	 */
	public function __construct( MediaType $mediaType, string $data ) {
		$this->mediaType = $mediaType;
		$this->data = $data;
	}

	/**
	 * Gets the media type of the encoded base64 string.
	 *
	 * @return MediaType
	 */
	public function getMediaType(): MediaType {
		return $this->mediaType;
	}

	/**
	 * Gets the original, encoded base64 string.
	 *
	 * @return string
	 */
	public function getData(): string {
		return $this->data;
	}

	/**
	 * Gets a decoded value of the base64 string.
	 *
	 * @return string
	 */
	public function getDecodedValue(): string {
		return base64_decode( $this->data );
	}

	/**
	 * Returns a data URL compliant with RFC 2397.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return sprintf(
			'%s%s%s%s%s',
			self::TOKEN_DATA_SCHEME,
			(string)$this->mediaType,
			self::TOKEN_BASE64_EXT,
			self::TOKEN_COMMA,
			$this->data
		);
	}
}
