<?php
/**
 * Benchmark template to show results
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
?>


<?php foreach(Benchmark::getInstance()->getSectionList() AS $section): ?>
	<h1> <?= $section->getName() ?> </h1>
	<?= $section->getDescription() ?>
	<table>
		<?php foreach($section->getCommandList() AS $command): ?>
		<tr>
			<td><?= $command; ?></td>
			<td><?= $command->getTime(); ?></td>
			<td>Ratio</td>
		</tr>
		<?php endforeach; ?>
	</table>
	

<?php endforeach; ?>