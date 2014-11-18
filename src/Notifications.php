<?php namespace Cartalyst\Notifications;
/**
 * Part of the Notifications package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the license.txt file.
 *
 * @package    Notifications
 * @version    1.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2014, Cartalyst LLC
 * @link       http://cartalyst.com
 */

class Notifications {

	/**
	 * Notifiers.
	 *
	 * @var array
	 */
	protected $notifiers = [];

	/**
	 * Adds the given notifier.
	 *
	 * @param  string  $type
	 * @param  \Cartalyst\Notifications\NotifierInterface $notifier
	 * @return void
	 */
	public function addNotifier($type, NotifierInterface $notifier)
	{
		$this->notifiers[$type] = $notifier;
	}

	/**
	 * Removes the given type from notifiers.
	 *
	 * @param  string  $type
	 * @return void
	 */
	public function removeNotifier($type)
	{
		unset($this->notifiers[$type]);
	}

	/**
	 * Returns all or a sepcific type of notifications.
	 *
	 * @param  string  $type
	 * @return array
	 */
	public function get($type = null)
	{
		$messages = [];

		foreach ($this->notifiers as $notifier)
		{
			$messages = array_merge_recursive($messages, $notifier->get());
		}

		if ($type)
		{
			$messages = array_filter($messages, function($message) use ($type)
			{
				return $message->area === $type;
			});
		}

		return $messages;
	}

	/**
	 * Returns the flash notifier.
	 *
	 * @return \Cartalyst\Notifications\FlashNotifier
	 */
	public function flash()
	{
		return $this->notifiers['flash'];
	}

	/**
	 * Dynamically forward notifications.
	 *
	 * @param  string  $method
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array([$this->notifiers['default'], '__call'], [$method, $parameters]);
	}

}
