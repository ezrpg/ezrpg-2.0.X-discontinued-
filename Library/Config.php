<?php

namespace ezRPG\Library;
use \Exception;

/**
 * Config
 */ 
class Config implements Interfaces\Config
{
	protected $config = array();
	protected $container;

	/**
     * @param array $config
     */
	public function __construct(Interfaces\Container $container)
	{
		$this->container = $container;
	}

	/**
     * @param string $offset
     */	
	public function offsetExists($offset)
	{
		return isset($this->config[$offset]);
	}

	/**
     * @param string $offset
     */
	public function offsetGet($offset)
	{
		return $this->config[$offset];
	}

	/**
     * @param string $offset
	 * @param string $value
     */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset)) {
			$this->config[] = $value;
		} else {
			$this->config[$offset] = $value;
		}
	}

	/**
     * @param string $offset
     */
	public function offsetUnset($offset)
	{
		unset($this->config[$offset]);
	}
	
	/**
     * @param string $key
	 * @param string $default
     */
	public function get($key, $default = null)
	{
		if (array_key_exists($key, $this->config)) {
			return $this->config[$key];
		}
		
		throw new Exception('Key "' . $key . '" not found within config');
	}
	
	/**
	 * Load configuration options from the database
	 * and add it to the runtime configuration.
	 */
	protected function addDatabaseConfig() {
		$this->config = array_merge_recursive(
			$this->config,
			$this->container['app']->getModel('Setting')->getAll()
		);
	}
}