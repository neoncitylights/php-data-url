# Changelog

## v3.0.0 (Unreleased)

### Deprecated
- This library's minimum PHP version was bumped from v8.1 to v8.2 (`neoncitylights/media-type` requires PHP v8.2).
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
- The library's CI now also runs builds against PHP v8.3 and PHP v8.4 (not just PHP v8.2).

## v2.0.0 (2020-11-12)
This release included a small unit test for retrieving a media type parameter value.

**Note**: This version did not include any major breaking changes; the version was mistakenly bumped from v1.0.0 to v2.0.0, instead of to v1.0.1.

## v1.0.0 (2020-)
Initial release of the library.
