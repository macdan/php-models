<?php

/**
 * Dk_Identity
 *
 * Any kind of object could have an identity, so we use
 * an interface to reflect that.
 */
interface Dk_Identity
{
	/**
	 * Identity
	 *
	 * All this method needs to do is return the object's Identity
	 */
	public function identity();
}
