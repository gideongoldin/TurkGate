<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>TurkGate</title>

    <link rel="stylesheet" href="stylesheets/styles.css">
    <link rel="stylesheet" href="stylesheets/pygment_trac.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-35980095-1']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>

  </head>
  <body>
    <div class="wrapper">
      <header>
        <h1>TurkGate</h1>
        <p>TurkGate: <strong>G</strong>rouping and <strong>A</strong>ccess <strong>T</strong>ools for <strong>E</strong>xternal surveys (for use with Amazon Mechanical <strong>Turk</strong>)</p>


<p>Created by: <br />
        <a href="https://github.com/AdamDarlow" style="font-weight: 700;">Adam Darlow</a> & <a href="https://github.com/gideongoldin" style="font-weight: 700;">Gideon Goldin</a></p>

        <p class="view">
          <a href="https://groups.google.com/forum/?fromgroups#!forum/turkgate" parent="_blank">View the Google Group</a><br />
          <a href="https://github.com/gideongoldin/TurkGate/wiki" parent="_blank">View the Wiki</a><br />
          <a href="https://github.com/gideongoldin/TurkGate">View the Project on GitHub</a>
        </p>

        <ul>
          <li><a href="https://github.com/gideongoldin/TurkGate/archive/v0.4.0.zip">Download <strong>ZIP File</strong></a></li>
          <li><a href="https://github.com/gideongoldin/TurkGate/archive/v0.4.0.tar.gz">Download <strong>TAR Ball</strong></a></li>
          <li><a href="https://github.com/gideongoldin/TurkGate">View On <strong>GitHub</strong></a></li>
        </ul>
        
	<p style="padding-left:1em; text-indent:-1em;">
  	  Please cite TurkGate as:
  	  <br />
	  Goldin, G., Darlow, A. (2013). TurkGate (Version 0.4.0) [Software]. Available from http://gideongoldin.github.com/TurkGate/
	</p>
	
      </header>
      <section>
        <h2>Welcome to TurkGate</h2>

<p>TurkGate, or Grouping and Access Tools for External surveys (for use
with Amazon Mechanical Turk), is for researchers that recruit through 
Mechanical Turk but run their studies on other sites. It provides better 
control and verification of Mechanical Turk workers' access to an 
external site. TurkGate was created for the purpose of conducting 
psychological experiments. As such, it provides easy-to-use, 
research-oriented features not included with Amazon Mechanical Turk.</p>

<h2>Screenshots</h2>
<img src="images/thumb.php?file=screen_admin_install.png&sizex=100" />
<?php

/* Displays details of GD support on your server */

echo '<div style="margin: 10px;">';

echo '<p style="color: #444444; font-size: 130%;">GD is ';

if (function_exists("gd_info")) {

	echo '<span style="color: #00AA00; font-weight: bold;">supported</span> by your server!</p>';

	$gd = gd_info();
        
	foreach ($gd as $k => $v) {

		echo '<div style="width: 340px; border-bottom: 1px solid #DDDDDD; padding: 2px;">';
		echo '<span style="float: left;width: 300px;">' . $k . '</span> ';

		if ($v)
			echo '<span style="color: #00AA00; font-weight: bold;">Yes</span>';
		else
			echo '<span style="color: #EE0000; font-weight: bold;">No</span>';

		echo '<div style="clear:both;"><!-- --></div></div>';
	}

} else {

	echo '<span style="color: #EE0000; font-weight: bold;">not supported</span> by your server!</p>';

}

echo '<p>by <a href="http://www.dagondesign.com">dagondesign.com</a></p>';

echo '</div>';

?>

<h2>What does TurkGate do?</h2>

<p>TurkGate serves three major functions:</p>

<ol>
<li>Preventing workers from accessing multiple surveys in the same group</li>
<li>Preventing workers from previewing surveys</li>
<li>Using secure codes to verify that workers completed their surveys</li>
</ol>

<p>First and foremost, TurkGate allows you to group HITs together, such
that workers may only access one survey within a group. As soon as a
worker has accessed a survey in a particular group, they are denied
future access to any survey in the same group. In certain research
settings, allowing a participant to access similar surveys (e.g.,
different versions of a study) is unacceptable. Amazon Mechanical Turk
does not offer a solution for this problem.</p>

<p>Likewise, exposing workers to parts of your survey prematurely 
(e.g., previews) may invalidate results. TurkGate enables the absolute
restriction of survey previews. It also prevents workers from returning
to a survey (even if they closed it accidentally).</p>

<p>Finally, TurkGate provides a method for assigning completion codes to
workers. Rather than using a static code (that can be shared on forums), 
TurkGate generates dynamic and encrypted codes that can later be
validated automatically.</p>

<h2>How does TurkGate Work?</h2>

<p>After performing a web-based installation, TurkGate initializes a 
database on your server to track workers, HITs, and groups. Any number 
of coordinated researchers can share one such installation. The 
researchers get easy to use web-based tools for creating HITs with 
TurkGate access  control, providing workers with dynamic, secure 
completion codes, and verifying those completion codes. </p>



      </section>

      <footer>
        <p><small>Hosted on GitHub Pages &mdash; Theme by <a href="https://github.com/orderedlist">orderedlist</a></small></p>
      </footer>
    </div>
    <script src="javascripts/scale.fix.js"></script>
    
  </body>
</html>
