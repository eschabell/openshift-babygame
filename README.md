Update: June 2018 moved to [Gitlab](https://gitlab.com/eschabell/openshift-babygame)


Babygame on OpenShift Express
============================
This git repository helps you get up and running quickly w/ a Babygame installation
on OpenShift Express.  The backend database is MySQL and the database name is the
same as your application name (using $_ENV['OPENSHIFT_APP_NAME']).


Install with one click
----------------------
[![Click to install OpenShift](http://launch-shifter.rhcloud.com/launch/light/Click to install.svg)](https://openshift.redhat.com/app/console/application_type/custom?&cartridges[]=php-5.3&initial_git_url=https://github.com/eschabell/openshift-babygame.git&name=babygame)

Note: after creation need to add `mysql-5.1` cartridge.

That's it, you can now checkout your application at:

    http://babygame-$your_domain.rhcloud.com/babygame.php


Manual install on OpenShift
---------------------------
Create a php-5.3 application

    rhc app create -t php-5.3 babygame

Add MySQL support to your application

    rhc cartridge add mysql-5.1 -a babygame

Pull in the babygame project code

    cd babaygame
    
    git remote add upstream -m master git://github.com/eschabell/openshift-babygame.git
    
    git pull -s recursive -X theirs upstream master
    
    git push


NOTES:

GIT_ROOT/.openshift/action_hooks/build:
    This script is executed with every 'git push'.  Feel free to modify this script
    to learn how to use it to your advantage.  By default, this script will create
    the database tables that this example uses.

