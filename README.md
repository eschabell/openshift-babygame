Babygame on OpenShift Express
============================
The Babygame is a simple PHP application that allows you to guess the birthdate
of your of a child. The name, date, time and sex of the baby are inputed and
posted for viewing.  As the due date (configurable, see README) approaches, you
will see the guesses turn red, the predicted due date will be shown in green.
It's just a fun way to get everyone involved in the birth of your child!

This git repository helps you get up and running quickly w/ a Babygame
installation on OpenShift Express.  The backend database is MySQL and the
database name is the same as your application name (using
$_ENV['OPENSHIFT_APP_NAME']).


Running on OpenShift
----------------------------

Create an account at http://openshift.redhat.com/

Create a php-5.3 application

    rhc-create-app -l $username -a babygame -t php-5.3

Add MySQL support to your application

    rhc-ctl-app -l $username -a babygame -e add-mysql-5.1

Add this upstream babygame repo

    cd babygame
    git remote add upstream -m master git@github.com:eschabell/openshift-babygame.git
    git pull -s recursive -X theirs upstream master
    # note that the git pull above can be used later to pull updates to babygame
    
Then push the repo upstream

    git push origin

That's it, you can now checkout your application at (default admin account is
admin/admin):

    http://babygame-$your_domain.rhcloud.com

NOTES:

GIT_ROOT/.openshift/action_hooks/build:
    This script is executed with every 'git push'.  Feel free to modify this script
    to learn how to use it to your advantage.  By default, this script will create
    the database tables that this example uses.

    If you need to modify the schema, you could create a file 
    GIT_ROOT/.openshift/action_hooks/alter.sql and then use
    GIT_ROOT/.openshift/action_hooks/build to execute that script (make susre to
    back up your application + database w/ rhc-snapshot first :) )

