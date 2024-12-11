<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\DataUrl;
use Neoncitylights\DataUrl\DataUrlParser;
use Neoncitylights\DataUrl\InvalidDataUrlSyntaxException;
use Neoncitylights\MediaType\MediaType;
use Neoncitylights\MediaType\MediaTypeParser;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Neoncitylights\DataUrl\DataUrlParser
 * @uses \Neoncitylights\DataUrl\DataUrl
 * @uses \Neoncitylights\DataUrl\InvalidDataUrlSyntaxException
 * @uses \Neoncitylights\MediaType\MediaType
 */
class DataUrlParserTest extends TestCase {
	/**
	 * @covers ::parseOrThrow
	 * @covers ::parseMediaTypeAndBase64
	 * @covers ::getMediaType
	 * @dataProvider provideValidTextBasedDataUrls
	 */
	public function testParseValidDataUrls( $expectedDataUrlObject, $validDataUrl ) {
		$parser = new DataUrlParser(
			new MediaTypeParser()
		);

		$this->assertEqualsCanonicalizing(
			$expectedDataUrlObject,
			$parser->parseOrThrow( $validDataUrl )
		);
	}

	/**
	 * @covers ::parseOrThrow
	 * @covers \Neoncitylights\DataUrl\InvalidDataUrlSyntaxException
	 * @dataProvider provideInvalidDataUrls
	 */
	public function testParseInvalidDataUrls( $invalidDataUrl ) {
		$this->expectException( InvalidDataUrlSyntaxException::class );

		$parser = new DataUrlParser(
			new MediaTypeParser()
		);
		$parser->parseOrThrow( $invalidDataUrl );
	}

	public function provideValidTextBasedDataUrls() {
		yield [
			new DataUrl(
				new MediaType( 'text', 'plain', [ 'charset' => 'US-ASCII' ] ),
				''
			),
			'data:,',
		];
		yield [
			new DataUrl(
				new MediaType( 'text', 'plain', [ 'charset' => 'US-ASCII' ] ),
				''
			),
			" \n\r\t\0\x0Bdata:, \n\r\t\0\x0B",
		];
		yield [
			new DataUrl(
				new MediaType( 'text', 'plain', [] ),
				'SGVsbG8sIFdvcmxkIQ=='
			),
			"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
		];
		yield [
			new DataUrl(
				new MediaType(
					'text', 'plain',
					[ 'charset' => 'UTF-8' ]
				),
				'VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg=='
			),
			'data:text/plain;charset=UTF-8;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg=='
		];
	}

	public function provideInvalidDataUrls() {
		yield [ '' ];
		yield [ ' \n\r\t\0\x0B' ];
		yield [ 'foo' ];
		yield [ 'data:test' ];
	}
}
