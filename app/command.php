<?php
/**
 * Benchmark command in a section
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
	protected $parent;
	protected $name;
	protected $command;
	protected $time;
	protected $iteration;
	
	const XML_NODE = 'command';
	
	public function __construct(Section $parent, $name, $command)
	{
		$this->setParent($parent);
		$this->setName($name);
		$this->setCommand($command);
		$this->setIteration(1000);
	}
	
	public function setParent(Section $parent)
	{
		$this->parent = $parent;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function setCommand($command)
	{
		$this->command = trim(preg_replace('/\t+/', '', $command));
	}
	
	public function setIteration($iteration)
	{
		$this->iteration = intval($iteration);
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getCommand()
	{
		return $this->command;
	}
	
	public function getIteration()
	{
		return $this->iteration;
	}
	
	public function getTime()
	{
		return $this->time;
	}
	
	public function benchmark()
	{
		// Avoid showing command results
		ob_start();
		
		$hookCommandAll = "";
		
		foreach ($this->parent->getHookList(Hook::TYPE_BEFORE) AS $hook)
		{
			$hookCommandAll .= $hook->getCommand();
		}
		
		// Attention to the scope of variables and the position of eval command
		eval($hookCommandAll);
		
		$timeStart = microtime(true);

		for ($benchmarkLoop = 0; $benchmarkLoop < $this->iteration; $benchmarkLoop++)
		{
			// Eval is Evil
			eval($this->command);
			
			foreach ($this->parent->getHookList(Hook::TYPE_LOOP_END) AS $hook)
				eval($hook->getCommand());
		}
		
		$timeEnd = microtime(true);
		$this->time = $timeEnd - $timeStart;
		
		// Clean buffer, we don't need to show command results
		ob_end_clean();
		
		return $this->getTime();
	}
	
	public function __toString()
	{
		if (!empty($this->name))
			return $this->name;
		else
			return $this->command;
	}
}