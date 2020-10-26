<?php

namespace Neoncitylights\Base64String\Tests;

use Neoncitylights\Base64String\MimeType;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass Neoncitylights\Base64String\MimeType
 */
class MimeTypeTest extends TestCase {
	/**
	 * @covers ::newFromString
	 * @dataProvider provideValidObjects
	 */
	public function testIsMimeTypeObject( $mimeTypeObject ) {
		$this->assertInstanceOf( MimeType::class, MimeType::newFromString( $mimeTypeObject ) );
	}

	/**
	 * @covers ::getType
	 * @dataProvider provideTypes
	 */
	public function testGetType( $expectedType, $actualType ) {
		$this->assertEquals(
			$expectedType,
			$actualType
		);
	}

	/**
	 * @covers ::getSubType
	 * @dataProvider provideSubTypes
	 */
	public function testGetSubType( $expectedSubType, $actualSubType ) {
		$this->assertEquals(
			$expectedSubType,
			$actualSubType
		);
	}

	/**
	 * @covers ::getParameters
	 * @dataProvider provideParameters
	 */
	public function testGetParameters( $expectedParameters, $actualParameters ) {
		$this->assertEquals(
			$expectedParameters,
			$actualParameters
		);
	}

	/**
	 * @covers ::getParameterValue
	 * @dataProvider provideParameterValues
	 */
	public function testGetParameterValue( $expectedParameterValue, $actualParameterValue ) {
		$this->assertEquals(
			$expectedParameterValue,
			$actualParameterValue
		);
	}

	public function provideValidObjects() {
		return [
			[ "text/plain;charset=UTF-8" ],
			[ "application/xhtml+xml" ],
			[ "application/vnd.openxmlformats-officedocument.presentationml.presentation" ],
		];
	}

	public function provideTypes() {
		return [
			[
				'text',
				MimeType::newFromString( "text/plain" )->getType(),
			],
			[
				'application',
				MimeType::newFromString( "application/xhtml+xml" )->getType(),
			],
			[
				'application',
				MimeType::newFromString( "application/vnd.openxmlformats-officedocument.presentationml.presentation" )->getType(),
			],
		];
	}

	public function provideSubTypes() {
		return [
			[
				'plain',
				MimeType::newFromString( "text/plain" )->getSubType(),
			],
			[
				'xhtml+xml',
				MimeType::newFromString( "application/xhtml+xml" )->getSubType(),
			],
			[
				'vnd.openxmlformats-officedocument.presentationml.presentation',
				MimeType::newFromString( "application/vnd.openxmlformats-officedocument.presentationml.presentation" )->getSubType(),
			],
		];
	}

	public function provideParameters() {
		return [
			[ 
				[
					'charset' => 'UTF-8'
				],
				MimeType::newFromString( "text/plain;charset=UTF-8" )->getParameters(),
			],
			[
				[],
				MimeType::newFromString( "text/plain" )->getParameters(),
			],
		];
	}

	public function provideParameterValues() {
		return [
			[ 
				'UTF-8',
				MimeType::newFromString( "text/plain;charset=UTF-8" )->getParameterValue( 'charset' ),
			],
			[
				null,
				MimeType::newFromString( "text/plain" )->getParameterValue( 'charset' )
			]
		];
	}
}
