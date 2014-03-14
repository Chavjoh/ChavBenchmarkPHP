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
	/**
	 * Singleton instance of the class
	 *
	 * @var Benchmark
	 */
	protected static $instance = null;

	/**
	 * Section list
	 * 
	 * @var Section[]
	 */
	protected $sectionList;
	
	/**
	 * Create a new benchmark. 
	 * Load benchmark from file defined in FILE_BENCHMARK constant
	 */
	protected function __construct()
	{
		$this->sectionList = array();
		$this->loadBenchmark();
	}
	
	/**
	 * Load benchmark from file defined in FILE_BENCHMARK constant
	 */
	protected function loadBenchmark()
	{
		// Get benchmark content in XML
		$contentXml = file_get_contents(FILE_BENCHMARK);

		// Read XML file
		$benchmarkXml = new SimpleXMLElement($contentXml);
		
		// List all sections
		foreach ($benchmarkXml->children() as $section) 
		{
			// Get name and description attribute
			$name = (isset($section['name'])) ? $section['name'] : '';
			$description = (isset($section['description'])) ? $section['description'] : '';
			
			// Create and add the section to the list
			$this->sectionList[] = $currentSection = new Section($name, $description);
			
			// List all commands in the current section
			foreach ($section->children() as $command) 
			{
				// If the current command is a hook
				if ($command->getName() == Hook::XML_NODE)
				{
					// Get type attribute
					$type = (isset($command['type'])) ? $command['type'] : '';
					
					// Create and register the hook in the current section
					$currentSection->addHook(new Hook($command->__toString(), $type));
				}
				// If the current command is a benchmark command
				else if ($command->getName() == Command::XML_NODE)
				{
					// Get name attribute
					$name = (isset($command['name'])) ? $command['name'] : '';
					
					// Create the benchmark command
					$currentCommand = new Command($currentSection, $name, $command->__toString());
					
					// Set iteration number if defined
					if (isset($command['iteration']))
						$currentCommand->setIteration($command['iteration']);
					
					// Add command to current section
					$currentSection->addCommand($currentCommand);
				}
			}
		}
	}
	
	/**
	 * Get singleton instance of the class
	 * 
	 * @return Benchmark Singleton instance
	 */
	public static function getInstance()
	{
		// Create instance if not yet created
		if (static::$instance === null)
			static::$instance = new Benchmark();

		return static::$instance;
	}
	
	/**
	 * Execute the entire benchmark
	 */
	public function executeBenchmark()
	{
		foreach ($this->sectionList as $section)
		{
			$section->benchmark();
		}
	}
	
	/**
	 * Get the section list
	 *
	 * @return Section[] Section list
	 */
	public function getSectionList()
	{
		return $this->sectionList;
	}
}

