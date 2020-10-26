<?php

namespace Neoncitylights\Base64String\Tests;

use Neoncitylights\Base64String\Base64String;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Neoncitylights\Base64String\Base64String
 */
class Base64StringTest extends TestCase {
	/**
	 * @covers ::newFromDataUrl
	 */
	public function testNewFromDataUrlSuccessful() {
		$base64String = Base64String::newFromDataUrl( "data:image/png;base64,ehsadjads" );

		return $this->assertInstanceOf( Base64String::class, $base64String );
	}

	/**
	 * @covers ::newFromDataUrl
	 * @dataProvider provideInvalidBase64Values
	 */
	public function testNewFromDataUrlFailed( $invalidBase64Value ) {
		return $this->assertNull( Base64String::newFromDataUrl( $invalidBase64Value ) );
	}

	/**
	 * @covers ::getMimeType
	 * @dataProvider provideMimeTypes
	 */
	public function testGetMimeType( $dataUrl, $actualMimeType ) {
		$base64String = Base64String::newFromDataUrl( $dataUrl );

		$this->assertEquals( $base64String->getMimeType(), $actualMimeType );
	}

	/**
	 * @covers ::doesMimeTypeMatch
	 * @dataProvider provideMimeTypes
	 */
	public function testIsMimeType( $dataUrl, $actualMimeType ) {
		$base64String = Base64String::newFromDataUrl( $dataUrl );

		$this->assertTrue( $base64String->doesMimeTypeMatch( $actualMimeType ) );
	}

	/**
	 * @covers ::getDecodedValue
	 * @dataProvider providePlainTextExamples
	 * @dataProvider provideHtmlExamples
	 */
	public function testGetDecodedValue( $dataUrl, $expectedDecodedValue ) {
		$base64String = Base64String::newFromDataUrl( $dataUrl );
		$this->assertEquals(
			$base64String->getDecodedValue(),
			$expectedDecodedValue
		);
	}

	public function provideInvalidBase64Values() {
		return [
			[ '' ],
			[ 'test' ],
			[ ',,foobar,,' ],
			[ 'foo,bar' ],
			[ ',,,,,' ],
		];
	}

	public function provideMimeTypes() {
		return [
			[
				"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==",
				"image/png"
			],
		];
	}

	public function providePlainTextExamples() {
		return [
			[
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
				"Hello, World!"
			],
			[
				"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
				"The five boxing wizards jump quickly."
			],
			[
				"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
				"This Pangram contains four a's, one b, two c's, one d, thirty e's, six f's, five g's, seven h's, eleven i's, one j, one k, two l's, two m's, eighteen n's, fifteen o's, two p's, one q, five r's, twenty-seven s's, eighteen t's, two u's, seven v's, eight w's, two x's, three y's, & one z."
			],
		];
	}

	public function provideHtmlExamples() {
		return [
			[
				"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
				"<h1>Hello, World!</h1>"
			],
			[
				"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
				"<p>This is a paragraph element.</p>"
			],
			[
				"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
				"<p>\nThis is a paragraph element.\n</p>"
			]
		];
	}
}
