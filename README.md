TurkGate
=========

Grouping and Access Tools for External surveys (for use with Amazon Mechanical Turk)
------------------------------------------------------------------------------------

## Introduction
TurkGate, or Grouping and Access Tools for External surveys (for use with Amazon Mechanical Turk), is a collection of files that enable better control of access to external surveys. TurkGate was initially created for the purpose of conducting psychological experiments, and thus includes research-oriented features not included with Amazon Mechanical Turk.

## What does TurkGate do?
TurkGate's features can be divided into three major areas:

1. Assigning surveys to groups
2. Restricting survey previews
3. Verifying survey completion

First, TurkGate allows you group HITs together, such that workers may only access a single survey within a group. As soon as a worker has accessed a survey in a particular group, they are denied future access to that survey as well as any other surveys in the same group. In certain research settings, allowing the same participant to access identical surveys, different versions of a survey, or similar is unacceptable. Amazon Mechanical Turk does not offer a solution for this issue.

Likewise, it is often undesirable to allow a potential worker to view a survey prior to committing to it. TurkGate allows the absolute restriction of survey previews. It also prevents workers from returning to a survey, even if they closed it accidentally.

Finally, TurkGate provides a method for assigning completion codes to workers after they finish your surveys. Rather than using a static (i.e., insecure) code, TurkGate generates dynamic codes that can be validated against a particular worker.

## How does TurkGate Work?
Generally speaking, TurkGate functions via a set of files you store in Amazon Mechanical Turk and your external survey-hosting server. This external server may use SurveyMonkey, LimeSurvey, Qualtrics, or other custom survey software. In particular, TurkGate comprises a simple database, a few server-side PHP scripts, and a couple of HIT templates.

## Getting Started
To get started, please visit the main [Table of Contents](Home) for this wiki.
