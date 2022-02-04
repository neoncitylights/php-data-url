<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\InvalidDataUrlSyntaxException;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Neoncitylights\DataUrl\InvalidDataUrlSyntaxException
 */
class InvalidDataUrlSyntaxExceptionTest extends TestCase {
	/**
	 * @covers ::__construct
	 */
	public function testConstructor(): void {
		$this->expectException( InvalidDataUrlSyntaxException::class );
		throw new InvalidDataUrlSyntaxException(
			'A valid data URL must not be an empty string.', '');
	}
}
