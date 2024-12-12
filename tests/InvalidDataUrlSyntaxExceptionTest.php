<?php

namespace Neoncitylights\DataUrl\Tests;

use Neoncitylights\DataUrl\InvalidDataUrlSyntaxException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass( InvalidDataUrlSyntaxException::class )]
class InvalidDataUrlSyntaxExceptionTest extends TestCase {

	public function testConstructor(): void {
		$this->expectException( InvalidDataUrlSyntaxException::class );
		throw new InvalidDataUrlSyntaxException(
			'A valid data URL must not be an empty string.', '');
	}
}
