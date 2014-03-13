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
	protected $command;
	protected $type;
	
	const XML_NODE = 'hook';
	const TYPE_BEFORE = 'before';
	
	public function __construct($command, $type)
	{
		$this->setCommand($command);
		$this->setType($type);
	}
	
	public function setCommand($command)
	{
		$this->command = trim(preg_replace('/\t+/', '', $command));
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getCommand()
	{
		return $this->command;
	}
	
	public function getType()
	{
		return $this->type;
	}
}
