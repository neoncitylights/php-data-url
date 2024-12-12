<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\DataUrl;
use Neoncitylights\DataUrl\DataUrlParser;
use Neoncitylights\MediaType\MediaType;
use Neoncitylights\MediaType\MediaTypeParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( DataUrl::class )]
#[UsesClass( DataUrlParser::class )]
#[UsesClass( MediaType::class )]
#[UsesClass( MediaTypeParser::class )]
class DataUrlTest extends TestCase {

	/** @var DataUrlParser|null */
	private static $dataUrlParser;

	public static function setUpBeforeClass(): void {
		self::$dataUrlParser = new DataUrlParser(
			new MediaTypeParser()
		);
	}

	public static function tearDownAfterClass(): void {
		self::$dataUrlParser = null;
	}

	public function testConstructor(): void {
		$this->assertInstanceOf(
			DataUrl::class,
			new DataUrl(
				new MediaType( 'text', 'plain', [ 'charset' => 'UTF-8' ] ),
				'hello world!'
			)
		);
	}

	#[DataProvider( "provideValidDataUrls" )]
	public function testGetMediaType( string $validDataUrl ): void {
		$this->assertInstanceOf(
			MediaType::class,
			self::$dataUrlParser->parse( $validDataUrl )->getMediaType()
		);
	}

	#[DataProvider( "provideMediaTypeEssences" )]
	public function testGetMediaTypeEssence( string $expectedMediaTypeEssence, string $validDataUrl ): void {
		$this->assertEquals(
			$expectedMediaTypeEssence,
			self::$dataUrlParser->parse( $validDataUrl )->getMediaType()->getEssence()
		);
	}

	#[DataProvider( "provideMediaTypeParameters" )]
	public function testGetMediaTypeParameterValue( string $expectedParameter, string $expectedParameterValue, string $validDataUrl ): void {
		$this->assertEquals(
			$expectedParameterValue,
			self::$dataUrlParser->parse( $validDataUrl )->getMediaType()->getParameterValue( $expectedParameter )
		);
	}

	#[DataProvider( "provideData" )]
	public function testGetData( string $expectedData, string $validDataUrl ): void {
		$this->assertEquals(
			$expectedData,
			self::$dataUrlParser->parse( $validDataUrl )->getData()
		);
	}

	#[DataProvider( "provideDecodedValues" )]
	public function testGetDecodedValue( string $expectedDecodedValue, string $validDataUrl ): void {
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

	#[DataProvider( "provideStrings" )]
	public function testToString( string $expectedDataUrl, string $actualValidDataUrl ): void {
		$this->assertEquals(
			$expectedDataUrl,
			(string)self::$dataUrlParser->parse( $actualValidDataUrl )
		);
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	public static function provideValidDataUrls(): array {
		return [
			'text/plain example #1: hello world: data url' => [
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
			],
			'text/plain example #2: pangram #1: data url' => [
				"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
			],
			'text/plain example #2: pangram #2: data url' => [
				"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
			],
			'text/html example #1: hello world: data url' => [
				"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
			],
			'text/html example #2: paragraph element: data url' => [
				"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
			],
			'text/html example #2: paragraph element with whitespace: data url' => [
				"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
			],
		];
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	public static function provideMediaTypeEssences(): array {
		return [
			[
				'text/plain',
				'data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==',
			],
			[
				'text/html',
				'data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==',
			],
		];
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	public static function provideMediaTypeParameters(): array {
		return [
			[
				'charset',
				'UTF-8',
				'data:text/plain;charset=UTF-8;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg=='
			],
		];
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	public static function provideData(): array {
		return [
			'text/plain example #1: hello world: data' => [
				"SGVsbG8sIFdvcmxkIQ==",
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
			],
			'text/plain example #2: pangram #1: data' => [
				"VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
				"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
			],
			'text/plain example #3: pangram #2: data' => [
				"VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
				"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
			],
			'text/html example #1: hello world: data' => [
				"PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
				"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg=="
			],
			'text/html example #2: paragraph element: data' => [
				"PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
				"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4="
			],
			'text/html example #2: paragraph element with whitespace: data' => [
				"PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
				"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg=="
			],
		];
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	public static function provideDecodedValues(): array {
		return [
			'text/plain example #1: hello world: decoded value' => [
				"Hello, World!",
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
			],
			'text/plain example #2: pangram #1: decoded value' => [
				"The five boxing wizards jump quickly.",
				"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
			],
			'text/plain example #3: pangram #2: decoded value' => [
				"This Pangram contains four a's, one b, two c's, one d, thirty e's, six f's, five g's, seven h's, eleven i's, one j, one k, two l's, two m's, eighteen n's, fifteen o's, two p's, one q, five r's, twenty-seven s's, eighteen t's, two u's, seven v's, eight w's, two x's, three y's, & one z.",
				"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
			],
			'text/html example #1: hello world: decoded value' => [
				"<h1>Hello, World!</h1>",
				"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg=="
			],
			'text/html example #2: paragraph element: decoded value' => [
				"<p>This is a paragraph element.</p>",
				"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4="
			],
			'text/html example #2: paragraph element with whitespace: decoded value' => [
				"<p>\nThis is a paragraph element.\n</p>",
				"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg=="
			],
		];
	}

	/**
	 * @return array<string,array<int,string>>
	 */
	public static function provideStrings(): array {
		return [
			'text/plain example #1: hello world' => [
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
				"data:text/plain;base64,SGVsbG8sIFdvcmxkIQ==",
			],
			'text/plain example #2: pangram #1' => [
				"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
				"data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==",
			],
			'text/plain example #2: pangram #2' => [
				"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
				"data:text/plain;base64,VGhpcyBQYW5ncmFtIGNvbnRhaW5zIGZvdXIgYSdzLCBvbmUgYiwgdHdvIGMncywgb25lIGQsIHRoaXJ0eSBlJ3MsIHNpeCBmJ3MsIGZpdmUgZydzLCBzZXZlbiBoJ3MsIGVsZXZlbiBpJ3MsIG9uZSBqLCBvbmUgaywgdHdvIGwncywgdHdvIG0ncywgZWlnaHRlZW4gbidzLCBmaWZ0ZWVuIG8ncywgdHdvIHAncywgb25lIHEsIGZpdmUgcidzLCB0d2VudHktc2V2ZW4gcydzLCBlaWdodGVlbiB0J3MsIHR3byB1J3MsIHNldmVuIHYncywgZWlnaHQgdydzLCB0d28geCdzLCB0aHJlZSB5J3MsICYgb25lIHou",
			],
			'text/html example #1: hello world' => [
				"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
				"data:text/html;base64,PGgxPkhlbGxvLCBXb3JsZCE8L2gxPg==",
			],
			'text/html example #2: paragraph element' => [
				"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
				"data:text/html;base64,PHA+VGhpcyBpcyBhIHBhcmFncmFwaCBlbGVtZW50LjwvcD4=",
			],
			'text/html example #2: paragraph element with whitespace' => [
				"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
				"data:text/html;base64,PHA+ClRoaXMgaXMgYSBwYXJhZ3JhcGggZWxlbWVudC4KPC9wPg==",
			]
		];
	}
}
