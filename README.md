TurkGate
=========

Grouping and Access Tools for External surveys (for use with Amazon Mechanical Turk)
------------------------------------------------------------------------------------

### Introduction

This package includes the code for controlling Amazon Mechanical Turk (mturk) workers' access to your surveys. It allows you to assign multiple HITs/surveys to the same group and only allows each worker to access a survey from that group once. Once a worker has accessed one survey in the group, they are denied future access to that survey and any other surveys in the same group.

Restricting access works by giving mturk workers a link to a special php page instead of directly to the survey you want them to participate in. This page checks in a database whether they were already granted access in the past with the same group name. If they weren't, the page records their current access and redirects them to the survey. If they were already granted access to a similar survey, they are not redirected to the survey and instead are asked to return the HIT.

This solution was designed for psychology studies where it is very important that all participants start with the same prior knowledge about the experiment. Therefore, the code also prevents previewing surveys or returning to a survey that was accidentally closed.

The package includes three packages: one for installation on a server, one for creating HITs with the mturk web interface, and one for creating HITs with the mturk command line tools.

The survey access system can be used in conjunction with any method of creating mturk HITs. Included in this package are templates for two HIT creation methods, using the [mturk web interface](https://requester.mturk.com/start) and using the [command line tools (CLT)](http://aws.amazon.com/developertools/694). These can be used as-is with only minimal changes, or they can be used as examples to learn how to create survey access HITs by other methods.

### Server Code

The server code is a set of three php files which serve to restrict access to your surveys (surveyForm.php), to generate confirmation codes for participants after they have completed a survey (surveyCompleted.php), and to verify lists of confirmation codes (verifyCodes.php). Also included in the package are server [installation instructions](code/INSTALLATION_INSTRUCTIONS.html) and a page for testing the installation (testDestination.php).

The server code only needs to be installed and set up once. A single installation should be sufficient for an entire department. Because survey access is granted or denied based on group names which can be any alphanumeric string, it is important that all researchers using a shared installation have a group naming convention that will prevent them from unintentionally using the same group name as someone else. This restriction is per installation. If two different departments use separate survey access installations, their group names will not affect each other.

### Web Interface HIT Code

This single HTML file contains javascript code ...

### Command Line Tools (CLT) HIT Code

...



