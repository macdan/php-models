<?php

/**
 * Dk_Model_IdentityMap_Array
 *
 * An Identity Map class that holds the objects in an array.
 */
class Dk_Model_IdentityMap_Array implements Dk_Model_IdentityMap
{
	/**
	 * @var array The array of objects held
	 */
	protected $_objects = array();
	
	/**
	 * Add Identity
	 */
	public function addIdentity( Dk_Identity $object )
	{
		$identity = $object->identity();
		
		if ( $this->hasIdentity( $object ) )
		{
			throw new Dk_Model_IdentityMapException(
				"$identity already present in identity map",
				Dk_Model_IdentityMapException::IDENTITY_PRESENT
			);
		}
		
		$this->_objects[ $identity ] = $object;
		return true;
	}
	
	/**
	 * Remove Identity
	 */
	public function removeIdentity( Dk_Identity $object )
	{
		if ( !$this->hasIdentity( $object ) )
		{
			$identity = $object->identity();
			throw new Dk_Model_IdentityMapException(
				"$identity not present in identity map",
				Dk_Model_IdentityMapException::IDENTITY_NOT_FOUND
			);
		}
		
		$objects = array();
		foreach ( $this->_objects as $storedObject )
		{
			if ( $storedObject == $object )
			{
				continue;
			}
			
			$objects[] = $storedObject;
		}
		$this->_objects = $objects;
	}
	
	/**
	 * Get Identity
	 */
	public function getIdentity( Dk_Identity $object )
	{
		if ( !$this->hasIdentity( $object ) )
		{
			$identity = $object->identity();
			throw new Dk_Model_IdentityMapException(
				"$identity not present in identity map",
				Dk_Model_IdentityMapException::IDENTITY_NOT_FOUND
			);
		}
		
		return $this->_objects[ $object->identity() ];
	}
	
	/**
	 * Has Identity
	 */
	public function hasIdentity( Dk_Identity $object )
	{
		return isset( $this->_objects[ $object->identity() ] ) ;
	}
}
