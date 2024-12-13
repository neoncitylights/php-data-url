<?php

namespace Neoncitylights\DataUrl;

use Neoncitylights\MediaType\MediaType;
use Neoncitylights\MediaType\MediaTypeParser;
use Neoncitylights\MediaType\MediaTypeParserException;
use function is_int;
use function strlen;
use function strrpos;
use function substr;
use function trim;

/**
 * Provides a data URL parser that is compliant to `RFC 2397`,
 * which defines the data URL scheme.
 *  - If no media type is provided, the default media type is assumed to be
 *    `text/plain;charset=US-ASCII`
 *
 * @see [IETF RFC 2397](https://datatracker.ietf.org/doc/html/rfc2397)
 * @license MIT
 */
class DataUrlParser {
	private MediaTypeParser $mediaTypeParser;

	public function __construct( ?MediaTypeParser $mediaTypeParser ) {
		$this->mediaTypeParser = $mediaTypeParser ?? new MediaTypeParser();
	}

	public function parseOrNull( string $dataUrl ): DataUrl|null {
		try {
			return $this->parseOrThrow( $dataUrl );
		} catch ( DataUrlParserException | MediaTypeParserException $e ) {
			return null;
		}
	}

	/**
	 * @throws InvalidDataUrlSyntaxException|MediaTypeParserException
	 * @deprecated Call parseOrThrow() instead.
	 * @codeCoverageIgnore
	 */
	public function parse( string $dataUrl ): DataUrl {
		return $this->parseOrThrow( $dataUrl );
	}

	/**
	 * @throws InvalidDataUrlSyntaxException|MediaTypeParserException
	 */
	public function parseOrThrow( string $dataUrl ): DataUrl {
		$trimmedDataUrl = trim( $dataUrl );

		if ( empty( $trimmedDataUrl ) ) {
			throw new DataUrlParserException(
				"A valid data URL must not be an empty string.",
				$dataUrl
			);
		}

		if ( strpos( $trimmedDataUrl, Token::UriScheme->value ) === false ) {
			throw new DataUrlParserException(
				"A valid data URL requires including a \"data:\" scheme.",
				$dataUrl
			);
		}

		$urlPath = substr( $trimmedDataUrl, strlen( Token::UriScheme->value ) );
		$lastCommaIndex = strrpos( $urlPath, Token::Comma->value );
		if ( $lastCommaIndex === false ) {
			throw new DataUrlParserException(
				"A valid data URL requires including a comma character.",
				$dataUrl
			);
		}

		$contentBeforeComma = substr( $urlPath, 0, $lastCommaIndex );
		$dataAfterComma = substr( $urlPath, $lastCommaIndex + 1 );

		return new DataUrl(
			$this->parseMediaTypeAndBase64( $contentBeforeComma ),
			$dataAfterComma
		);
	}

	/**
	 * @throws MediaTypeParserException
	 */
	private function parseMediaTypeAndBase64( string $content ): MediaType {
		$base64ExtIndex = strrpos( $content, Token::Base64Ext->value );
		if ( is_int( $base64ExtIndex ) ) {
			$mediaTypeString = substr( $content, 0, $base64ExtIndex );
			return $this->getMediaType( $mediaTypeString );
		}

		return $this->getMediaType( $content );
	}

	/**
	 * @throws MediaTypeParserException
	 */
	private function getMediaType( string $mediaTypeString ): MediaType {
		if ( empty( $mediaTypeString ) ) {
			return new MediaType( 'text', 'plain', [ 'charset' => 'US-ASCII', ] );
		}

		return $this->mediaTypeParser->parseOrThrow( $mediaTypeString );
	}
}
