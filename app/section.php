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
	protected $name;
	protected $description;
	protected $commandList;
	protected $time; // Min, Max
	
	const KEY_MIN = 'min';
	const KEY_MAX = 'max';
	
	public function __construct($name, $description)
	{
		$this->setName($name);
		$this->setDescription($description);
		$this->time = null;
		$this->commandList = array();
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function addCommand(Command $command)
	{
		$this->commandList[] = $command;
	}
	
	public function getCommandList()
	{
		return $this->commandList;
	}
	
	public function benchmark()
	{
		foreach ($this->getCommandList() as $command)
		{
			$time = $command->benchmark();
			
			if ($this->time == null)
			{
				$this->time = array(
					Section::KEY_MIN => $time,
					Section::KEY_MAX => $time
				);
			}
			else
			{
				if ($time < $this->time[Section::KEY_MIN])
					$this->time[Section::KEY_MIN] = $time;
				else if ($time > $this->time[Section::KEY_MAX])
					$this->time[Section::KEY_MAX] = $time;
			}
		}
	}
	
	public function getFatestTime()
	{
		return $this->time[Section::KEY_MIN];
	}
	
	public function getLongestTime()
	{
		return $this->time[Section::KEY_MAX];
	}
	
	public function getDivisionFactor()
	{
		$time = $this->getLongestTime();
		
		$timeFactor = array(
			1 => 's',
			0.001 => 'ms',
			0.0000001 => 'μs',
			0.0000000001 => 'ns'
		);
		
		foreach ($timeFactor AS $factor => $name)
		{
			if (($time / $factor) > 0)
				return array($factor, $name);
		}
		
		return array(null, null);
	}
}
