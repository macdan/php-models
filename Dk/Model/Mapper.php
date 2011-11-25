<?php

/**
 * Mapper Base Class
 */
class Dk_Model_Mapper
{
	protected $_identityMap;
	
	public function __construct( $options=array() )
	{
		if ( isset( $options['identityMap'] ) && $options['identityMap'] instanceof Dk_Model_IdentityMap )
		{
			$this->_identityMap = $options['identityMap'];
		}
		else
		{
			$this->_identityMap = new Dk_Model_IdentityMap_Array;
		}
	}
	
	/**
	 * Magic Call
	 *
	 * Handles get*Mapper() calls
	 *
	 * @param  string $property The property to set
	 * @param  array  $params The parameters the method was called with
	 * @return mixed  The method return value
	 */
	public function __call( $method, $params )
	{
		if ( preg_match( '/get(.+?)Mapper/', $method, $matches ) )
		{
			$model = $matches[1];
			
			if ( !isset( $this->_mappers[ $model ] ) )
			{
				$class = "Mapper_{$model}";
				$this->_mappers[ $model ] = new $class;
			}
			
			return $this->_mappers[ $model ];
		}
		
		throw new Exception( "Unknown method: {$method}" );
	}
	
	/**
	 * Find
	 */
	public function find( Dk_Identity $identity )
	{
//		if ( $this->_identityMap->hasIdentity( $identity ) )
//		{
//			return $this->_identityMap->getIdentity( $identity );
//		}
		
		$results = $this->_find( $identity );
//		foreach ( $results as $result )
//		{
//			$this->_identityMap->addIdentity( $result );
//		}
		return $results;
	}
}
