<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\DataUrl;
use Neoncitylights\DataUrl\DataUrlParser;
use Neoncitylights\MediaType\MediaType;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Neoncitylights\DataUrl\DataUrl
 * @uses \Neoncitylights\DataUrl\DataUrlParser
 * @uses \Neoncitylights\MediaType\MediaType
 */
class DataUrlTest extends TestCase {
	/** @var DataUrlParser|null */
	private static $dataUrlParser;

	public static function setUpBeforeClass(): void {
		self::$dataUrlParser = new DataUrlParser();
	}

	public static function tearDownAfterClass(): void {
		self::$dataUrlParser = null;
	}

	/**
	 * @covers ::__construct
	 */
	public function testConstructor() {
		$this->assertInstanceOf(
			DataUrl::class,
			new DataUrl(
				new MediaType( 'text', 'plain', [ 'charset' => 'UTF-8' ] ),
				'hello world!'
			)
		);
	}

	/**
	 * @covers ::getMediaType
	 * @covers ::__construct
	 * @dataProvider provideValidDataUrls
	 */
	public function testGetMediaType( $validDataUrl ) {
		$this->assertInstanceOf(
			MediaType::class,
			self::$dataUrlParser->parse( $validDataUrl )->getMediaType()
		);
	}

	/**
	 * @covers ::__construct
	 * @covers ::getMediaType
	 * @dataProvider provideMediaTypeEssences
	 */
	public function testGetMediaTypeEssence( $expectedMediaTypeEssence, $validDataUrl ) {
		$this->assertEquals(
			$expectedMediaTypeEssence,
			self::$dataUrlParser->parse( $validDataUrl )->getMediaType()->getEssence()
		);
	}

	/**
	 * @covers ::getMediaType
	 * @covers ::__construct
	 * @dataProvider provideMediaTypeParameters
	 */
	public function testGetMediaTypeParameterValue( $expectedParameter, $expectedParameterValue, $validDataUrl ) {
		$this->assertEquals(
			$expectedParameterValue,
			self::$dataUrlParser->parse( $validDataUrl )->getMediaType()->getParameterValue( $expectedParameter )
		);
	}

	/**
	 * @covers ::getData
	 * @covers ::__construct
	 * @dataProvider provideData
	 */
	public function testGetData( $expectedData, $validDataUrl ) {
		$this->assertEquals(
			$expectedData,
			self::$dataUrlParser->parse( $validDataUrl )->getData()
		);
	}

	/**
	 * @covers ::getDecodedValue
	 * @covers ::__construct
	 * @dataProvider provideDecodedValues
	 */
	public function testGetDecodedValue( $expectedDecodedValue, $validDataUrl ) {
		$this->assertEquals(
			$expectedDecodedValue,
			self::$dataUrlParser->parse( $validDataUrl )->getDecodedValue()
		);

		// test cache
		$this->assertEquals(
			$expectedDecodedValue,
			self::$dataUrlParser->parse( $validDataUrl )->getDecodedValue()
		);
	}

	/**
	 * @covers ::__toString
	 * @covers ::__construct
	 * @dataProvider provideStrings
	 * @uses \Neoncitylights\DataUrl\DataUrlParser
	 */
	public function testToString( $expectedDataUrl, $actualValidDataUrl ) {
		$this->assertEquals(
			$expectedDataUrl,
			(string)self::$dataUrlParser->parse( $actualValidDataUrl )
		);
	}

	public function provideValidDataUrls() {
		yield 'text/plain example #1: hello world: data url' => [
			"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
		];
		yield 'text/plain example #2: pangram #1: data url' => [
			"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
		];
		yield 'text/plain example #2: pangram #2: data url' => [
			"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
		];
		yield 'text/html example #1: hello world: data url' => [
			"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
		];
		yield 'text/html example #2: paragraph element: data url' => [
			"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
		];
		yield 'text/html example #2: paragraph element with whitespace: data url' => [
			"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
		];
	}

	public function provideMediaTypeEssences() {
		yield [
			'text/plain',
			'data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==',
		];
		yield [
			'text/html',
			'data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==',
		];
	}

	public function provideMediaTypeParameters() {
		yield [
			'charset',
			'UTF-8',
			'data:text/plain;charset=UTF-8;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg=='
		];
	}

	public function provideData() {
		yield 'text/plain example #1: hello world: data' => [
			"SGVsbG8sIFdvcmxkIQ==",
			"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
		];
		yield 'text/plain example #2: pangram #1: data' => [
			"VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
			"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
		];
		yield 'text/plain example #3: pangram #2: data' => [
			"VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
			"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
		];
		yield 'text/html example #1: hello world: data' => [
			"PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
			"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg=="
		];
		yield 'text/html example #2: paragraph element: data' => [
			"PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
			"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4="
		];
		yield 'text/html example #2: paragraph element with whitespace: data' => [
			"PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
			"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg=="
		];
	}

	public function provideDecodedValues() {
		yield 'text/plain example #1: hello world: decoded value' => [
			"Hello, World!",
			"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
		];
		yield 'text/plain example #2: pangram #1: decoded value' => [
			"The five boxing wizards jump quickly.",
			"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
		];
		yield 'text/plain example #3: pangram #2: decoded value' => [
			"This Pangram contains four a's, one b, two c's, one d, thirty e's, six f's, five g's, seven h's, eleven i's, one j, one k, two l's, two m's, eighteen n's, fifteen o's, two p's, one q, five r's, twenty-seven s's, eighteen t's, two u's, seven v's, eight w's, two x's, three y's, & one z.",
			"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
		];
		yield 'text/html example #1: hello world: decoded value' => [
			"<h1>Hello, World!</h1>",
			"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg=="
		];
		yield 'text/html example #2: paragraph element: decoded value' => [
			"<p>This is a paragraph element.</p>",
			"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4="
		];
		yield 'text/html example #2: paragraph element with whitespace: decoded value' => [
			"<p>\nThis is a paragraph element.\n</p>",
			"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg=="
		];
	}

	public function provideStrings() {
		yield 'text/plain example #1: hello world' => [
			"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
			"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
		];
		yield 'text/plain example #2: pangram #1' => [
			"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
			"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
		];
		yield 'text/plain example #2: pangram #2' => [
			"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
			"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
		];
		yield 'text/html example #1: hello world' => [
			"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
			"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
		];
		yield 'text/html example #2: paragraph element' => [
			"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
			"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
		];
		yield 'text/html example #2: paragraph element with whitespace' => [
			"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
			"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
		];
	}
}
