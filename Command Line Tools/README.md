This is a template for creating external HITs that go through TurkGate using the [Command Line Tools](http://aws.amazon.com/developertools/694).

1. Create a new directory for your HIT by going to the *hits* folder in your CLT installation and running *makeTemplate -target survey -template external_HIT* . This will create a folder named *survey* in the *hits* folder. If you already have a folder named *survey* or you want to change the name for any reason, change the *-target* parameter and see step 3.
2. Replace the files that end in *.input*, *.properties* and *.question* with the files from the template.
3. If you created the HIT with a name other than *survey*, change the names of the files to match the name of the HIT.
4. Update the *.properties* file with the properties you want for your survey. Don't change the *annotation* field.
5. In the *.input* file, replace *test* with your survey site identifier and *1* with your survey id. If you don't know what these are, consult your TurkGate administrator.
6. Also in the *.input* file, replace *group name* with the name of your survey group. See [Survey groups](https://github.com/gideongoldin/TurkGate/wiki/Survey%20groups) for more details. Make sure there is a tab separating the survey id from the group name and no other tabs in the line.

The HIT is now ready to be created like any other CLT HIT, using the *run* script. If this is the first time you are creating a TurkGate HIT, you might want to create it in the [requesters' sandbox](https://requestersandbox.mturk.com/) to try it out. You can do this using the *-sandbox* parameter of the *run* script.