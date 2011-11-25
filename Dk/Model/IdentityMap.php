<?php

/**
 * Dk_Model_IdentityMap
 *
 * An Identity Map could be any number of different mechanisms,
 * so we make this an interface.
 */
interface Dk_Model_IdentityMap
{
	public function addIdentity( Dk_Identity $object );
	public function removeIdentity( Dk_Identity $object );
	public function getIdentity( Dk_Identity $object );
	public function hasIdentity( Dk_Identity $object );
}

/**
 * Exception that our Identity Map classes would throw
 */
class Dk_Model_IdentityMapException extends Exception
{
	const IDENTITY_PRESENT = 1;
	const IDENTITY_NOT_FOUND = 2;
}

