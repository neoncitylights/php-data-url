<?php

namespace Neoncitylights\DataUrl;

/**
 * @internal
 * @license MIT
 */
enum Token: string {
	case Base64Ext = ';base64';
	case UriScheme = 'data:';
	case Comma = ',';
}
