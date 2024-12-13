# Changelog

## v3.0.0 (2024-12-12)

### Breaking changes
- This library's minimum PHP version was bumped from v8.1 to v8.2 (`neoncitylights/media-type` requires PHP v8.2).
- `InvalidDataUrlSyntaxException` was renamed to `DataUrlParserException`.
- `DataUrlParser->parse()`/`parseOrThrow()` can now also throw `MediaTypeParserException` if the media type cannot be parsed.

### Deprecated
- Calling `DataUrlParser->parse()` is now deprecated. Instead, call `DataUrlParser->parseOrThrow()`. This method been renamed to be more explicit in side effects (throwing an exception).
- Creating a new `DataUrlParser` instance without any arguments is now deprecated. Instead, pass in an instance of `Neoncitylights\MediaType\MediaTypeParser` as an argument, like so:
  ```php
  use Neoncitylights\DataUrl\DataUrlParser;
  use Neoncitylights\MediaType\MediaTypeParser;

  $dataUrlParser = new DataUrlParser( new MediaTypeParser() );
  ```

### Features
- `DataUrlParser` has a new instance method, `parseOrNull()`, which either returns `DataUrl` or `null`. This method is an alternative to `parseOrThrow()`.

### Internal changes
- The private properties of `MediaType` are now strongly type-hinted. Note that these were already technically typehinted since they were private, and the constructor signature was already typehinted.
- The library's CI now also runs builds against PHP v8.3 and PHP v8.4 (not just PHP v8.2).
- `mediawiki/mediawiki-sniffer` was bumped from v33.0.0 to v45.0.0.
- `mediawiki/minus-x` was bumped from v1.1.0 to v1.1.3.
- `php-parallel-lint/php-console-highlighter` was bumped from v0.5.0 to v1.0.0.
- `php-parallel-lint/php-parallel-lint` was bumped from v1.2.0 to v1.4.0.
- `phpunit/phpunit` was bumped from v9.4 to v11.5.1.

## v2.0.0 (2020-11-12)
This release included a small unit test for retrieving a media type parameter value.

**Note**: This version did not include any major breaking changes; the version was mistakenly bumped from v1.0.0 to v2.0.0, instead of to v1.0.1.

## v1.0.0 (2020-)
Initial release of the library.
