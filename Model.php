<?php

/**
 * Domain Model Base Class
 */
class Model
{
	/**
	 * @access protected
	 * @var Mapper If this model is to be lazy loaded, then this will be a reference to the Model's Mapper
	 */
	protected $__mapper = null;
	
	/**
	 * Proxy
	 * 
	 * @access protected
	 * @return null
	 */
	protected function __lazyLoad( $mapper=null )
	{
		if ( !$this->__mapper )
		{
			return;
		}
		
		$this->__mapper->find( $this );
		$this->__mapper = null;
	}
	
	/**
	 * Constructor
	 *
	 * Allow the model to be initialised from an array
	 * 
	 * @param array $array The array to take data from
	 */
	public function __construct( array $array=array() )
	{
		$this->fromArray( $array );
	}
	
	/**
	 * Magic Get
	 *
	 * Just fires the proxy method before setting the property
	 *
	 * @param string $property The property to set
	 * @return mixed $property The value of the property
	 */
	public function __get( $property )
	{
		$property = "_{$property}";
		
		if ( !property_exists( $this, $property ) )
		{
			throw new Exception( "Invalid property: {$property}\n" );
		}
		
		$this->__lazyLoad();
		
		return $this->$property;
	}
	
	/**
	 * Magic Set
	 *
	 * Just fires the proxy method before setting the property
	 *
	 * @param string $property The property to set
	 * @param mixed $property The value to set
	 */
	public function __set( $property, $value )
	{
		$property = "_{$property}";
		
		if ( !property_exists( $this, $property ) )
		{
			throw new Exception( "Invalid property: {$property}\n" );
		}
		
		$this->__lazyLoad();
		
		$this->$property = $value;
	}
	
	/**
	 * Magic Call
	 *
	 * Just fires the proxy method before calling the real method
	 *
	 * @param  string $property The property to set
	 * @param  array  $params The parameters the method was called with
	 * @return mixed  The method return value
	 */
	public function __call( $method, $params )
	{
		$this->__lazyLoad();
		
		return call_user_func_array( array( $this, $method ), $params );
	}
	
	/**
	 * From Array
	 *
	 * Populate the model with values from an array
	 * 
	 * @param array $array The array to take data from
	 */
	public function fromArray( array $array )
	{
		foreach ( $array as $property => $value )
		{
			$property = "_{$property}";
			
			if ( !property_exists( $this, $property ) )
			{
				throw new Exception( "Invalid property: {$property}\n" );
			}
			
			$this->$property = $value;
		}
	}
}

/**
 * The Product Model
 */
class Model_Product extends Model
{
	public $_id = null;
	public $_sku = null;
	
	public $_cldr = null;
	public $_title = null;
	public $_summary = null;
	
	public $_images = null;
}

/**
 * The Image Model
 */
class Model_Image extends Model
{
	protected $_id = null;
	protected $_cldr = null;
	protected $_src = null;
}

