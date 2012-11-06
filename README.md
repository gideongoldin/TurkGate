## Introduction
TurkGate, or Grouping and Access Tools for External surveys (for use
with Amazon Mechanical Turk), is for researchers that recruit through 
Mechanical Turk but run their studies on other sites. It provides better 
control and verification of Mechanical Turk workers' access to an 
external site. TurkGate was created for the purpose of conducting 
psychological experiments. As such, it provides easy-to-use, 
research-oriented features not included with Amazon Mechanical Turk.

## What does TurkGate do?

TurkGate comprises three major features:

1.  Limiting access to groups of surveys
2.  Preventing survey previews
3.  Verifying survey completion

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
of coordinated researchers can share one such installation. To control 
access to their surveys the researchers simply create their Mechanical 
Turk HITs using the templates provided for HIT creation via Mechanical 
Turk's web interface *or* its command line tools. 

## More information
Please visit the [TurkGate Wiki](https://github.com/gideongoldin/TurkGate/wiki "TurkGate Wiki") for more information.