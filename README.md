Babygame on OpenShift Express
============================

This git repository helps you get up and running quickly w/ a Babygame installation
on OpenShift Express.  The backend database is MySQL and the database name is the
same as your application name (using $_ENV['OPENSHIFT_APP_NAME']).


Running on OpenShift
----------------------------

Create an account at http://openshift.redhat.com/

Create a php-5.3 application

    rhc app create -a babygame -t php-5.3

Add MySQL support to your application

    rhc cartridge add -a babygame mysql-5.1

Pull in the babygame project code

    cd babaygame
    
    git remote add upstream -m master git://github.com/eschabell/openshift-babygame.git
    
    git pull -s recursive -X theirs upstream master
    
    git push
    
That's it, you can now checkout your application at:

    http://babygame-$your_domain.rhcloud.com/babygame.php


NOTES:

GIT_ROOT/.openshift/action_hooks/build:
    This script is executed with every 'git push'.  Feel free to modify this script
    to learn how to use it to your advantage.  By default, this script will create
    the database tables that this example uses.

    If you need to modify the schema, you could create a file 
    GIT_ROOT/.openshift/action_hooks/alter.sql and then use
    GIT_ROOT/.openshift/action_hooks/build to execute that script (make susre to
    back up your application + database w/ rhc-snapshot first :) )

