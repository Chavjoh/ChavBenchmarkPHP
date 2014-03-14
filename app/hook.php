<?php
/**
 * Benchmark hook
 *
 * @package ChavBenchmarkPHP
 * @author Chavaillaz Johan
 * @since 1.0.0
 * @license Apache 2.0 License
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

class Hook
{
	/**
	 * Hook command
	 *
	 * @var String
	 */
	protected $command;

	/**
	 * Hook type
	 * - Executed before the benchmark
	 * - Executed after each loop of the benchmark
	 *
	 * @var String
	 */
	protected $type;
	
	/**
	 * Node name in XML file
	 *
	 * @var String
	 */
	const XML_NODE = 'hook';

	/**
	 * Hook type executed before the benchmark
	 *
	 * @var String
	 */
	const TYPE_BEFORE = 'before';

	/**
	 * Hook type executed after each loop of the benchmark
	 *
	 * @var String
	 */
	const TYPE_LOOP_END = 'loop_end';
	
	/**
	 * Create a new hook with command and type
	 *
	 * @param String $command Command to be executed for this hook
	 * @param String $type Type of the hook
	 */
	public function __construct($command, $type)
	{
		$this->setCommand($command);
		$this->setType($type);
	}
	
	/**
	 * Set hook command.
	 * Tabs are removed.
	 *
	 * @param String $command Hook command to be executed
	 */
	public function setCommand($command)
	{
		$this->command = trim(preg_replace('/\t+/', '', $command));
	}
	
	/**
	 * Set hook type
	 *
	 * @param String $type Hook type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}
	
	/**
	 * Get hook command
	 *
	 * @return String Hook command
	 */
	public function getCommand()
	{
		return $this->command;
	}
	
	/**
	 * Get hook type
	 *
	 * @return String Hook type
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 * Get hook type description
	 *
	 * @return String Hook type description
	 */
	public function getTypeDescription()
	{
		switch ($this->getType())
		{
			case Hook::TYPE_BEFORE:
				return 'Executed before benchmark';
			case Hook::TYPE_LOOP_END:
				return 'Executed after each loop';
		}
	}
}
