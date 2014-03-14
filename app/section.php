<?php
/**
 * Benchmark section
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

class Section
{
	/**
	 * Section name
	 *
	 * @var String
	 */
	protected $name;

	/**
	 * Section description
	 *
	 * @var String
	 */
	protected $description;

	/**
	 * Hook list in the section
	 *
	 * @var Hook[]
	 */
	protected $hookList;

	/**
	 * Command list in the section
	 *
	 * @var Command[]
	 */
	protected $commandList;

	/**
	 * Execution time statistics (MIN, MAX)
	 *
	 * @var Float[]
	 */
	protected $time;
	
	/**
	 * Key to indentify minimum execution time in time statistics variable
	 *
	 * @var String
	 */
	const KEY_MIN = 'min';

	/**
	 * Key to indentify maximum execution time in time statistics variable
	 *
	 * @var String
	 */
	const KEY_MAX = 'max';

	/**
	 * Create a new section with name and description
	 *
	 * @param String $name Section name
	 * @param String $description Description name
	 */
	public function __construct($name, $description)
	{
		$this->setName($name);
		$this->setDescription($description);
		$this->hookList = array();
		$this->commandList = array();
	}
	
	/**
	 * Set name
	 *
	 * @param String $name Section name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Set description
	 *
	 * @param String $description Section description
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	/**
	 * Get name
	 *
	 * @return String Section name 
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Get description
	 *
	 * @return String Section description
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Add a command to the section
	 *
	 * @param Command $command Command to add
	 */
	public function addCommand(Command $command)
	{
		$this->commandList[] = $command;
	}
	
	/**
	 * Add a hook to the section
	 *
	 * @param Hook $hook Hook to add
	 */
	public function addHook(Hook $hook)
	{
		$this->hookList[] = $hook;
	}
	
	/**
	 * Get command list
	 *
	 * @return Command[] Command list in the section
	 */
	public function getCommandList()
	{
		return $this->commandList;
	}
	
	/**
	 * Get hook list
	 *
	 * @param String $type If defined, ask to return the list of hook with this type
	 */
	public function getHookList($type = null)
	{
		if ($type == null)
			return $this->hookList;
		
		$hookList = array();
		
		foreach ($this->hookList as $hook)
		{
			if ($hook->getType() == $type)
				$hookList[] = $hook;
		}
		
		return $hookList;
	}
	
	/**
	 * Execute benchmark on the entire section
	 */
	public function benchmark()
	{
		$this->time = null;

		foreach ($this->getCommandList() as $command)
		{
			// Benchmark command
			$time = $command->benchmark();
			
			// First loop, define time statistics
			if ($this->time == null)
			{
				$this->time = array(
					Section::KEY_MIN => $time,
					Section::KEY_MAX => $time
				);
			}
			else
			{
				// Save time when record
				if ($time < $this->time[Section::KEY_MIN])
					$this->time[Section::KEY_MIN] = $time;
				else if ($time > $this->time[Section::KEY_MAX])
					$this->time[Section::KEY_MAX] = $time;
			}
		}
	}
	
	/**
	 * Get the fastest command execution time during the section benchmark
	 *
	 * @return Float Fatest execution time in seconds (accurate to the nearest microsecond)
	 */
	public function getFastestTime()
	{
		return $this->time[Section::KEY_MIN];
	}

	/**
	 * Get the longest command execution time during the section benchmark
	 *
	 * @return Float Longest execution time in seconds (accurate to the nearest microsecond)
	 */
	public function getLongestTime()
	{
		return $this->time[Section::KEY_MAX];
	}
	
	/**
	 * Get division factor to normalize all execution time in the section benchmark.
	 * Managed factor : s, ms, μs, ns
	 *
	 * @return Array Division factor and unit name in an array
	 */
	public function getDivisionFactor()
	{
		$time = $this->getLongestTime();
		
		$timeFactor = array(
			'1.0' => 's',
			'0.001' => 'ms',
			'0.0000001' => 'μs',
			'0.0000000001' => 'ns'
		);
		
		foreach ($timeFactor AS $factor => $name)
		{
			if (($time / $factor) > 1)
				return array($factor, $name);
		}
		
		return array(null, null);
	}
	
	/**
	 * Get factor of the command compared to the best of the section.
	 * Used for percentage of performance in the benchmark.
	 *
	 * @param Command $command Corresponding command
	 * @return Float Factor compared to the best
	 */
	public function getCommandFactor(Command $command)
	{
		return round(($command->getTime() / $this->getFastestTime()), 5);
	}
	
	/**
	 * Get the ratio of the command compared to the best of the section
	 *
	 * @param Command $command Corresponding command
	 * @return Float Ratio between 0 (best performance) and 1 (worst performance)
	 */
	public function getCommandRatio(Command $command)
	{
		$deltaMax = $this->getLongestTime() - $this->getFastestTime();
		$delta = $command->getTime() - $this->getFastestTime();
		return $delta / $deltaMax;
	}
	
	/**
	 * Get the hue for a command corresponding to result in benchmark
	 *
	 * @param Command $command Corresponding command
	 * @return String Hue between 0 (red) and 120 (green)
	 */
	public function getCommandResultHue(Command $command)
	{
		$ratio = $this->getCommandRatio($command);
		$ratioInverse = 1 - $ratio;
		
		return $ratioInverse * 120;
	}
	
	/**
	 * Get the text color for a command corresponding to result in benchmark
	 *
	 * @param Command $command Corresponding command
	 * @return String color (black or white)
	 */
	public function getCommandResultColor(Command $command)
	{
		if ((1 - $this->getCommandRatio($command)) > 0.3)
			return 'black';
		
		return 'white';
	}
	
	/**
	 * Get command execution time with normalized unity
	 *
	 * @param Command $command Corresponding command
	 * @return Execution time with normalized unity
	 */
	public function getCommandTimeWithUnity(Command $command)
	{
		list($factor, $unity) = $this->getDivisionFactor();
		return round($command->getTime() / $factor, 3).' '.$unity;
	}
}
