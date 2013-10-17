* Main Page: http://turkgate.net
* GitHub Page: https://github.com/gideongoldin/TurkGate
* Wiki: https://github.com/gideongoldin/TurkGate/wiki
* Google Group: https://groups.google.com/forum/?fromgroups#!forum/turkgate

## Introduction
TurkGate, or Grouping and Access Tools for External surveys (for use
with Amazon Mechanical Turk), is for researchers that recruit through 
Mechanical Turk but run their studies on other sites. It provides better 
control and verification of Mechanical Turk workers' access to an 
external site. TurkGate was created for the purpose of conducting 
psychological experiments. As such, it provides easy-to-use, 
research-oriented features not included with Amazon Mechanical Turk.

![TurkGate Screenshot](https://raw.github.com/wiki/gideongoldin/TurkGate/screens/generate.jpg)

## What does TurkGate do?

TurkGate serves three major functions:

1.  Preventing workers from accessing multiple surveys in the same group
2.  Preventing workers from previewing surveys
3.  Using secure codes to verify that workers completed their surveys

First and foremost, TurkGate allows you to group HITs together, such
that workers may only access one survey within a group. As soon as a
worker has accessed a survey in a particular group, they are denied
future access to any survey in the same group. In certain research
settings, allowing a participant to access similar surveys (e.g.,
different versions of a study) is unacceptable. Amazon Mechanical Turk
does not offer a solution for this problem.

Likewise, exposing workers to parts of your survey prematurely 
(e.g., previews) may invalidate results. TurkGate enables the absolute
restriction of survey previews. It also prevents workers from returning
to a survey (even if they closed it accidentally).

Finally, TurkGate provides a method for assigning completion codes to
workers. Rather than using a static code (that can be shared on forums), 
TurkGate generates dynamic and encrypted codes that can later be
validated automatically.

## How does TurkGate Work?

After performing a web-based installation, TurkGate initializes a 
database on your server to track workers, HITs, and groups. Any number 
of coordinated researchers can share one such installation. The 
researchers get easy to use web-based tools for creating HITs with 
TurkGate access  control, providing workers with dynamic, secure 
completion codes, and verifying those completion codes. 
