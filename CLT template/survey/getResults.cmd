@echo off
rem
rem Copyright 2008 Amazon Technologies, Inc.
rem 
rem Licensed under the Amazon Software License (the "License");
rem you may not use this file except in compliance with the License.
rem You may obtain a copy of the License at:
rem 
rem http://aws.amazon.com/asl
rem 
rem This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES
rem OR CONDITIONS OF ANY KIND, either express or implied. See the
rem License for the specific language governing permissions and
rem limitations under the License.
rem 
rem


set OLDDIR=%CD%
cd ..\..
cd bin
call getResults %1 %2 %3 %4 %5 %6 %7 %8 %9 -successfile %OLDDIR%\survey.success -outputfile %OLDDIR%\survey.results 
cd %OLDDIR%
