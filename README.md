ChavBenchmarkPHP
================

PHP benchmark tool to compare PHP code performance, under Apache 2.0 License.

![Screenshot](/screenshot/ChavBenchmarkPHP.png "Screenshot")

## Requirements

* PHP 5.3

## How to use

Copy the repository to your server and launch **index.php**.

If you want to make your own benchmarks, edit **benchmark.xml**. As you can see in this file, benchmarks are cut in different section and each section contains commands. Each of these is benchmarked between them and results are shown.

A section is composed as follows :
```
<section name="Your section name" description="Your section description">
  <command>
    <![CDATA[
    Your script (commands) to compare
    ]]>
  </command>
  <command>
    <![CDATA[
    Your other script (commands) to compare
    ]]>
  </command>
  ...
</section>
```

In addition of the command list, you can add hooks.

## Hooks

There's two types of hooks :
* Hooks executed before benchmark
* Hooks executed after each loop of the benchmark

Like you see, the hooks are scripts that are executed at one point of the benchmark. You can for example declare or increment variables in hooks.

To add a hook, create a ```<hook type="...">...</hook>``` tag in the concerned section, like :
```
<section name="Your section name" description="Your section description">
  <hook type="before">
  	<![CDATA[
  	$answer = 42;
  	]]>
  </hook>
  <command>
  ...
  </command>
  ...
</section>
```

See current **benchmark.xml** for more details and examples.

## Feedback

Don't hesitate to fork this project, improve it and make a pull request.
