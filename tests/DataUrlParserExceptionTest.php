<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\DataUrlParserException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( DataUrlParserException::class )]
class DataUrlParserExceptionTest extends TestCase {

	public function testConstructor(): void {
		$this->expectException( DataUrlParserException::class );
		throw new DataUrlParserException(
			'A valid data URL must not be an empty string.', '');
	}
}
