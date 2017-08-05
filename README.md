# concert-tracker
A web-based PHP solution to tracking concerts you've attended and artists you want to see.

Allows for simple management of artists and concerts for multiple users, along with the ability to import and export their own data.

# Dependencies
* A web server with PHP support (Tested on Apache2 and Nginx with PHP 5.6)
* PostgreSQL or MySQL (Others may work, these are the only ones tested)

# Installation
The recommended installation is fairly straightforward:

1. Create a database role and a database that that role has full access to.
2. Extract the source to the web server, and point a virtual host to the concert-tracker root.
3. Navigate to the system in a web browser, and run the installation script using the database info from earlier.

At this point, the system should be fully operational.

# Bugs
This is work-in-progress software, and as such is liable to have some bugs.  No warranty is provided. 
However, if any bugs are found, please [report them](https://github.com/Erdubya/concert-tracker/issues) so that they can be patched.
