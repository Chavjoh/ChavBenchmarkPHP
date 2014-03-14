<?php
/**
 * Benchmark command
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

class Command
{
	/**
	 * Parent section of the command
	 *
	 * @var Section
	 */
	protected $parent;

	/**
	 * Command name
	 *
	 * @var String
	 */
	protected $name;

	/**
	 * Command to execute in the benchmark
	 *
	 * @var String
	 */
	protected $command;

	/**
	 * Execution time in second
	 * accurate to the nearest microsecond
	 *
	 * @var Float
	 */
	protected $time;

	/**
	 * Number of iteration for the benchmark
	 *
	 * @var Integer
	 */
	protected $iteration;
	
	/**
	 * Node name in XML file
	 *
	 * @var String
	 */
	const XML_NODE = 'command';
	
	/**
	 * Create a new command with default iteration number
	 *
	 * @param Section $parent Parent section of the command
	 * @param String $name Name of the command
	 * @param String $command Command to execute in the benchmark
	 */
	public function __construct(Section $parent, $name, $command)
	{
		$this->setParent($parent);
		$this->setName($name);
		$this->setCommand($command);
		$this->setIteration(BENCHMARK_ITERATION);
		$this->time = -1;
	}
	
	/**
	 * Set parent section
	 *
	 * @param Section $parent Parent section of the command
	 */
	public function setParent(Section $parent)
	{
		$this->parent = $parent;
	}
	
	/**
	 * Set command name
	 *
	 * @param String $name Name of the command
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/** 
	 * Set command to execute in benchmark.
	 * Tabs are removed.
	 *
	 * @param String $command Command to execute in the benchmark
	 */
	public function setCommand($command)
	{
		$this->command = trim(preg_replace('/\t+/', '', $command));
	}
	
	/**
	 * Set benchmark iteration number 
	 *
	 * @param Integer $iteration Number of iteration for the benchmark
	 */
	public function setIteration($iteration)
	{
		$this->iteration = intval($iteration);
	}
	
	/**
	 * Get command name
	 *
	 * @return String Command name
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/** 
	 * Get command to execute
	 *
	 * @return String Benchmark command
	 */
	public function getCommand()
	{
		return $this->command;
	}
	
	/**
	 * Get number of iteration
	 *
	 * @return Integer Benchmark iteration number
	 */
	public function getIteration()
	{
		return $this->iteration;
	}
	
	/**
	 * Get benchmark execution time. 
	 * Return -1 if benchmark are not executed yet.
	 *
	 * @return Float Execution time in seconds (accurate to the nearest microsecond)
	 */
	public function getTime()
	{
		return $this->time;
	}
	
	/**
	 * Execute benchmark
	 *
	 * @return Float Execution time in seconds (accurate to the nearest microsecond)
	 */
	public function benchmark()
	{
		// Avoid showing command results
		// Be careful, we don't see errors by this way
		ob_start();
		
		// Store all "before" hooks commands
		$hookCommandAll = "";
		
		// Fill the $hookCommandAll variable with all "before" hooks
		foreach ($this->parent->getHookList(Hook::TYPE_BEFORE) AS $hook)
			$hookCommandAll .= $hook->getCommand();
		
		// Execute "before" hooks
		// Attention to the scope of variables and the position of eval command
		eval($hookCommandAll);
		
		// Start time of the benchmark
		$timeStart = microtime(true);

		// Loop for each iteration
		// Be careful, if command set the $benchmarkLoop variable, it will be an unexpected behavior
		for ($benchmarkLoop = 0; $benchmarkLoop < $this->iteration; $benchmarkLoop++)
		{
			// Eval is Evil
			eval($this->command);
			
			// Execute hooks
			foreach ($this->parent->getHookList(Hook::TYPE_LOOP_END) AS $hook)
				eval($hook->getCommand());
		}
		
		// Execution time calculation
		$timeEnd = microtime(true);
		$this->time = $timeEnd - $timeStart;
		
		// Clean buffer, we don't need to show command results
		ob_end_clean();
		
		// Return execution time
		return $this->getTime();
	}
	
	/**
	 * Show name if defined or otherwise command
	 *
	 * @return String String representation of the class
	 */
	public function __toString()
	{
		if (!empty($this->name))
			return $this->name;
		else
			return $this->command;
	}
}
