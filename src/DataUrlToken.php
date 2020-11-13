<?php

namespace Neoncitylights\DataUrl;

/**
 * @internal
 * @license MIT
 */
interface DataUrlToken {
	public const TOKEN_BASE64_EXT = ';base64';
	public const TOKEN_DATA_SCHEME = 'data:';
	public const TOKEN_COMMA = ',';
}
