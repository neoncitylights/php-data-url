# DataUrl
A small PHP library for dealing with data URLs, which contain a media type and an encoded base64 string.

This library fully conforms to RFC 2397.

## Install
```
composer require neoncitylights/data-url
```

## Usage
```php
<?php

use Neoncitylights\DataUrl\DataUrl;

$dataUrl = DataUrl::newFromString( 'data:text/plain;base64,VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==' );

print( $dataUrl->getMediaType()->getEssence() );
// 'text/plain'

print( $dataUrl->getData() );
// `VGhlIGZpdmUgYm94aW5nIHdpemFyZHMganVtcCBxdWlja2x5Lg==`

print( $dataUrl->getDecodedValue() );
// 'The five boxing wizards jump quickly.'
```

## References
* Masinter, L., &amp; X. (1998, August). The "data" URL scheme. Retrieved October 30, 2020, from https://tools.ietf.org/html/rfc2397
