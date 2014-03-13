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
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	
	<title>PHP Benchmark</title>
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Main design -->
	<link href="style/design.css" rel="stylesheet">
	<link href="style/jumbotron-narrow.css" rel="stylesheet">
	
	<!-- Bootstrap -->
	<link href="plugin/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<div class="header">
			<h3 class="text-muted">ChavBenchmarkPHP</h3>
		</div>

		<div class="jumbotron">
			<h1>PHP Benchmark</h1>
			<p class="lead">
				To configure benchmark, use the <strong>benchmark.xml</strong> file.
			</p>
		</div>
		
		<?php foreach(Benchmark::getInstance()->getSectionList() AS $section): ?>
			<h1> <?= $section->getName() ?> </h1>
			<blockquote><?= $section->getDescription() ?></blockquote>
			
			<?php
			$hookList = $section->getHookList();
			if (count($hookList) > 0):
			?>
				<h3>Hooks</h3>
				<table class="table">
				<?php foreach($hookList AS $hook):  ?>
					<tr>
						<td class="hook_type"><?= $hook->getTypeDescription() ?></td>
						<td><pre><?= $hook->getCommand() ?></pre></td>
					</tr>		
				<?php endforeach; ?>
				</table>
			<?php endif; ?>
			<h3>Results</h3>
			<table class="table">
				<?php foreach($section->getCommandList() AS $command): ?>
				<tr>
					<td class="time"><?= $section->getCommandTimeWithUnity($command) ?></td>
					<td class="ratio">
						<div class="percent" style="
							border: 1px solid hsl(<?= $section->getCommandResultHue($command) ?>, 50%, 50%);
							background-color: hsl(<?= $section->getCommandResultHue($command) ?>, 100%, 50%);
							color: <?= $section->getCommandResultColor($command) ?>;">
							<?= round($section->getCommandFactor($command) * 100) ?>%
						</div>
					</td>
					<td><pre><?= $command ?></pre></td>
				</tr>
				<?php endforeach; ?>
			</table>
		<?php endforeach; ?>

		<div class="footer">
			<p>By <a href="http://www.chavjoh.ch">Johan Chavaillaz</a>, under Apache 2.0 license.</p>
		</div>

	</div>
</body>
</html>
