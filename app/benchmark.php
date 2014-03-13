<?php
/**
 * Benchmark storage class
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
class Benchmark
{
	protected static $instance = null;
	protected $sectionList;
	
	protected function __construct()
	{
		$this->sectionList = array();
		
		$this->loadBenchmark();
	}
	
	protected function loadBenchmark()
	{
		// Get benchmark content in XML
		$contentXml = file_get_contents(FILE_BENCHMARK);

		// Read XML file
		$benchmarkXml = new SimpleXMLElement($contentXml);
		
		// List all sections
		foreach ($benchmarkXml->children() as $section) 
		{
			$name = (isset($section['name'])) ? $section['name'] : '';
			$description = (isset($section['description'])) ? $section['description'] : '';
			
			$this->sectionList[] = $currentSection = new Section($name, $description);
			
			// List all commands in the current section
			foreach ($section->children() as $command) 
			{
				if ($command->getName() == Hook::XML_NODE)
				{
					$type = (isset($command['type'])) ? $command['type'] : '';
					
					$currentSection->addHook(new Hook($command->__toString(), $type));
				}
				else if ($command->getName() == Command::XML_NODE)
				{
					$name = (isset($command['name'])) ? $command['name'] : '';
					
					$currentCommand = new Command($currentSection, $name, $command->__toString());
					
					if (isset($command['iteration']))
						$currentCommand->setIteration($command['iteration']);
					
					$currentSection->addCommand($currentCommand);
				}
			}
		}
	}
	
	public static function getInstance()
	{
		if (static::$instance === null)
		{
			static::$instance = new Benchmark();
		}
		
		return static::$instance;
	}
	
	public function executeBenchmark()
	{
		foreach ($this->sectionList as $section)
		{
			$section->benchmark();
		}
	}
	
	public function getSectionList()
	{
		return $this->sectionList;
	}
}

