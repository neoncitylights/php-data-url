<?php

namespace Neoncitylights\Base64String;

/**
 * @license MIT
 */
class MediaType {
	private const TOKEN_TYPE_SEPARATOR = '/';
	private const TOKEN_DELIMETER = ';';
	private const TOKEN_EQUAL = '=';

	/** @var string */
	private $type;

	/** @var string */
	private $subType;

	/** @var string[] */
	private $parameters;

	/**
	 * @param string $type
	 * @param string $subType
	 * @param array $parameters
	 */
	public function __construct( string $type, string $subType, array $parameters ) {
		$this->type = $type;
		$this->subType = $subType;
		$this->parameters = $parameters;
	}

	/**
	 * @param string $input
	 * @return self
	 */
	public static function newFromString( string $input ) : self {
		$parts = explode( self::TOKEN_DELIMETER, $input );
		list( $type, $subType ) = explode( self::TOKEN_TYPE_SEPARATOR, $parts[0] );

		unset( $parts[0] );

		$parameters = [];
		foreach ( $parts as $part ) {
			$paramParts = explode( self::TOKEN_EQUAL, $part );
			$parameters[$paramParts[0]] = $paramParts[1];
		}

		return new self( $type, $subType, $parameters );
	}

	/**
	 * @see https://mimesniff.spec.whatwg.org/#type
	 * @return string
	 */
	public function getType() : string {
		return $this->type;
	}

	/**
	 * @see https://mimesniff.spec.whatwg.org/#subtype
	 * @return string
	 */
	public function getSubType() : string {
		return $this->subType;
	}

	/**
	 * @see https://mimesniff.spec.whatwg.org/#mime-type-essence
	 * @return string
	 */
	public function getEssence() : string {
		return "{$this->type}/{$this->subType}";
	}

	/**
	 * @see https://mimesniff.spec.whatwg.org/#parameters
	 * @return array
	 */
	public function getParameters() : array {
		return $this->parameters;
	}

	/**
	 * @param string $param
	 * @return string|null
	 */
	public function getParameterValue( string $param ) : ?string {
		return $this->parameters[$param] ?? null;
	}
}
