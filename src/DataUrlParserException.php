<?php

namespace Neoncitylights\DataUrl;

use InvalidArgumentException;

/**
 * @license MIT
 */
class DataUrlParserException extends InvalidArgumentException {
	public function __construct( string $errorMessage, string $invalidDataUrl ) {
		parent::__construct(
			"{$errorMessage} " .
			"The invalid data URL that was passed is: {$invalidDataUrl}"
		);
	}
}
