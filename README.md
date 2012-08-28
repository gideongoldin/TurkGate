# ABOUT

## Introduction
TurkGate, or Grouping and Access Tools for External surveys (for use with Amazon Mechanical Turk), is a collection of files that enable better control of access to external surveys. TurkGate was initially created for the purpose of conducting psychological experiments, and thus includes research-oriented features not included with Amazon Mechanical Turk.

## What does TurkGate do?
TurkGate comprises three major features:

1. Assigning surveys to groups
2. Restricting survey previews
3. Verifying survey completion

First, TurkGate allows you to group HITs together, such that workers may only access a single survey within a given. As soon as a worker has accessed a survey in a particular group, they are denied future access to that survey as well as any other surveys in the same group. In certain research settings, allowing the same participant to access the same survey more than once, different versions of a single survey, or surveys that are very similar to one another is unacceptable. Amazon Mechanical Turk does not offer a solution for this issue.

Likewise, it is often undesirable to allow a potential worker to view any part of a survey prior to committing to it. TurkGate allows the absolute restriction of survey previews. It also prevents workers from returning to a survey, even if they closed it accidentally.

Finally, TurkGate provides a method for assigning completion codes to workers after they finish your surveys. Rather than using a static (i.e., insecure) code, TurkGate generates dynamic codes that can later be automatically validated.

## How does TurkGate Work?
TurkGate functions via a set of files you store in Amazon Mechanical Turk and your external survey-hosting server. This external server may use SurveyMonkey, LimeSurvey, Qualtrics, or any other survey software. Technically, TurkGate consists of a simple database, a few server-side PHP scripts, and a couple of HIT templates.

---

# INSTALLATION

## Requirements
### Server installation
1. HTTP- and PHP-enabled web server (Apache, ...)
2. SQL database

### HIT creation
1. [Amazon Mechanical Turk (AMT)](https://www.mturk.com/mturk/welcome) requester account
2. Recommended: [Command Line Tools](http://aws.amazon.com/developertools/694) for AMT

## Server installation
1. Create two new tables in your SQL database, one named *SurveyAccess* and one named *SurveySites*. *SurveyAccess* records whenever a worker accesses a site in order to deny them future access to other sites in the same group. It needs the columns *workerId*, *group*, and *survey*. *SurveySites* lists the websites that host your surveys. It needs the columns *code* and *url* which are described below. It is recommended to put these tables in their own separate database for better security, because of Step 4.
2. Download the [TurkGate server](https://github.com/gideongoldin/TurkGate/tree/master/server%20code) and place it anywhere within your web domain root folder.
3. Enable read permissions for the downloaded files and the folder they are in.
4. Update the database credentials at the top of *surveyAccess.php* to enable it to access the tables you just created.

## Adding a survey site
The *SurveySites* lists all of the sites where your surveys reside, e.g. Surveymonkey or a Limesurvey installation. The server code uses this database to translate a site code and survey id into URL to the survey. Each site listing consists of a *code* and a *url*. The code is what users should put in their template when they want to access a survey from a given site together with a survey id. For example, the templates use the survey code `test` and `id` 1 to denote the test destination included with this installation. The server code uses the *SurveySites* database to translate that into the URL `http://your.installation/testDestination.php?id=1`.

Each site added to *SurveySites* needs two fields:

1. `code` - The name your researchers will use to identify the site in their HITs, e.g. 'test'.
2. `url` - The URL that the `code` is translated to. This URL isn't just the domain name, it is everything except the survey id that the researcher supplies separately. In the test example, the value of `url` is `http://your.installation/testDestination.php?id=`. There should always be an `=` at the end because the id should be assigned to some variable. It will be appended to the URL even if there is no variable to assign it to, which cold cause problems. Typically, the easiest way to get the correct URL is to go to a survey, copy the URL, rearrange the variables so that the survey identifier is last and remove the value of the survey identifier, but leave the variable and equal sign. What's left is what you should put in the database as the site's `url`.

## Testing the installation
To test the installation, first add the test site to *SurveySites* as described above. You can then try accessing the test page using the following URL with `your.installation` replace by your domain and installation path:

`http://your.installation/surveyForm.php?source=ext&survey=test%201&group=group%20name&workerId=ABCD&assignmentId=1234`

You should see an appropriate entry added to the *SurveyAccess* table. If you try to enter the same URL again (but not refresh because you were redirected) you should receive a message telling you that access was denied. By changing either *workerId* or *group* you should regain access to the test page. In addition to testing access after first setting up TurkGate, you should also test access to new sites after you add them.