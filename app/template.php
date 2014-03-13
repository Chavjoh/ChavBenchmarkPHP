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
				...
			</p>
		</div>
		
		<?php foreach(Benchmark::getInstance()->getSectionList() AS $section): ?>
			<?php
			list($factor, $unity) = $section->getDivisionFactor();
			?>
			<h1> <?= $section->getName() ?> </h1>
			<?= $section->getDescription() ?>
			<table class="table table-striped">
				<?php foreach($section->getCommandList() AS $command): ?>
				<tr>
					<td><?= round($command->getTime() / $factor, 5) ?> <?= $unity ?></td>
					<td><?= round(($command->getTime() / $section->getFatestTime()) * 100, 1) ?>%</td>
					<td><?= nl2br($command) ?></td>
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
