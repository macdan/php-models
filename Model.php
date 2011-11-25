<?php

/**
 * Domain Model Base Class
 */
class Model implements Dk_Identity
{
/*	const STATE_NEW = 1;     // In memory only
	const STATE_CLEAN = 2;   // Stored
	const STATE_DIRTY = 3;   // Stored with changes in memory
	const STATE_DELETED = 4; // Marked for deletion
	
	protected $_model_states = array(
		self::STATE_NEW,
		self::STATE_CLEAN,
		self::STATE_DIRTY,
		self::STATE_DELETED
	);
	protected $_model_state = self::STATE_NEW;
	
	public function modelState( $state = null )
	{
		if ( $state )
		{
			if ( !in_array( $state, $this->_model_states ) )
			{
				throw new Exception( "Invalid state: $state" );
			}
			
			$this->_model_state = $state;
			return $this->_model_state;
		}
		
		return $this->_model_state;
	}*/
	
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
		
		$mapper = $this->__mapper;
		$this->__mapper = null;
		
		$mapper->find( $this );
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
	
	// ------------------------------------------------------------------------
	
	public function identity()
	{
		return $this->_id;
	}
}

/**
 * The Product Model
 */
class Model_Product extends Model
{
	public $_id = null;
	public $_barcode = null;
	public $_stock = null;
	
	public $_cldr = null;
	public $_title = null;
	public $_summary = null;
	
	public $_images = null;
	
	public function identity()
	{
		return 'urn:product:' . $this->_cldr . ':' . $this->_id;
	}
}

/**
 * The Image Model
 */
class Model_Image extends Model
{
	protected $_id = null;
	protected $_src = null;
}

