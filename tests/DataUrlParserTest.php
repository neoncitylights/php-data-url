<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\DataUrl;
use Neoncitylights\DataUrl\DataUrlParser;
use Neoncitylights\DataUrl\DataUrlParserException;
use Neoncitylights\MediaType\MediaType;
use Neoncitylights\MediaType\MediaTypeParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( DataUrlParser::class )]
#[UsesClass( DataUrl::class )]
#[UsesClass( DataUrlParserException::class )]
#[UsesClass( MediaType::class )]
#[UsesClass( MediaTypeParser::class )]
class DataUrlParserTest extends TestCase {

	#[DataProvider( "provideValidTextBasedDataUrls" )]
	public function testParseValidDataUrls( DataUrl $expectedDataUrlObject, string $validDataUrl ): void {
		$parser = new DataUrlParser( new MediaTypeParser() );
		$this->assertEqualsCanonicalizing(
			$expectedDataUrlObject,
			$parser->parseOrThrow( $validDataUrl )
		);
	}

	#[DataProvider( "provideInvalidDataUrls" )]
	public function testParseInvalidDataUrls( string $invalidDataUrl ): void {
		$this->expectException( DataUrlParserException::class );

		$parser = new DataUrlParser( new MediaTypeParser() );
		$parser->parseOrThrow( $invalidDataUrl );
	}

	/**
	 * @return array<string>
	 */
	public static function provideValidTextBasedDataUrls(): array {
		return [
			[
				new DataUrl(
					new MediaType( 'text', 'plain', [ 'charset' => 'US-ASCII' ] ),
					''
				),
				'data:,',
			],
			[
				new DataUrl(
					new MediaType( 'text', 'plain', [ 'charset' => 'US-ASCII' ] ),
					''
				),
				" \n\r\t\0\x0Bdata:, \n\r\t\0\x0B",
			],
			[
				new DataUrl(
					new MediaType( 'text', 'plain', [] ),
					'SGVsbG8sIFdvcmxkIQ=='
				),
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
			],
			[
				new DataUrl(
					new MediaType(
						'text', 'plain',
						[ 'charset' => 'UTF-8' ]
					),
					'VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg=='
				),
				'data:text/plain;charset=UTF-8;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg=='
			]
		];
	}

	/**
	 * @return array{string}
	 */
	public static function provideInvalidDataUrls(): array {
		return [
			[ '' ],
			[ ' \n\r\t\0\x0B' ],
			[ 'foo' ],
			[ 'data:test' ],
		];
	}
}
