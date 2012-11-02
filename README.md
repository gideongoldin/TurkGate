## Introduction
TurkGate, or Grouping and Access Tools for External surveys (for use
with Amazon Mechanical Turk), is a simple library that enables better
control of access to external surveys. TurkGate was created for the
purpose of conducting psychological experiments. As such, it provides
easy-to-use, research-oriented features not included with Amazon
Mechanical Turk.

## What does TurkGate do?

TurkGate comprises three major features:

1.  Assigning surveys to groups
2.  Restricting survey previews
3.  Verifying survey completion

First and foremost, TurkGate allows you to group HITs together, such
that workers may only access one survey within a group. As soon as a
worker has accessed a survey in a particular group, they are denied
future access to any survey in the same group. In certain research
settings, allowing a participant to access similar surveys (e.g.,
different versions of a study) is unacceptable. Amazon Mechanical Turk
does not offer a solution for this problem.

Likewise, exposing workers to parts of your survey (e.g., previews)
prematurely may invalidate results. TurkGate enables the absolute
restriction of survey previews. It also prevents workers from returning
to a survey (even if they closed it accidentally).

Finally, TurkGate provides a method for assigning completion codes to
workers. Rather than using a static (i.e., insecure) code, TurkGate
generates dynamic and encrypted codes that can later be validated
automatically.

## How does TurkGate Work?

TurkGate provides requesters with templates for using Mechanical Turk
via the web interface *or* the command line tools. After performing a
web-based installation, TurkGate initializes a database on your server
to track workers, HITs, and groups.

## More information
Please visit the [TurkGate Wiki](https://github.com/gideongoldin/TurkGate/wiki "TurkGate Wiki") for more information.