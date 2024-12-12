# DataUrl
![Packagist Version](https://img.shields.io/packagist/v/neoncitylights/data-url)
![GitHub](https://img.shields.io/github/license/neoncitylights/php-data-url)
[![PHP Composer](https://github.com/neoncitylights/php-data-url/actions/workflows/php.yml/badge.svg)](https://github.com/neoncitylights/php-data-url/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/neoncitylights/php-data-url/branch/main/graph/badge.svg?token=IdWjeqFQcS)](https://codecov.io/gh/neoncitylights/php-data-url)

A small PHP library for dealing with data URLs, which contain a media type and an encoded base64 string.

This library fully conforms to [RFC 2397](https://datatracker.ietf.org/doc/html/rfc2397).

## Install
This requires a minimum PHP version of v8.2.

```
composer require neoncitylights/data-url
```

## Usage
```php
<?php

use Neoncitylights\DataUrl\DataUrlParser;
use Neoncitylights\MediaType\MediaTypeParser;

$dataUrlParser = new DataUrlParser( new MediaTypeParser() );
$dataUrl = $dataUrlParser->parse( 'data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==' );

print( $dataUrl->getMediaType()->getEssence() );
// 'text/plain'

print( $dataUrl->getData() );
// `VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==`

print( $dataUrl->getDecodedValue() );
// 'The five boxing wizards jump quickly.'
```

## License
This software is licensed under the MIT license ([`LICENSE`](./LICENSE) or
<https://opensource.org/license/mit/>).

### Contribution
Unless you explicitly state otherwise, any contribution intentionally submitted
for inclusion in the work by you, as defined in the MIT license, shall be
licensed as above, without any additional terms or conditions.
