# Bowling League Manager

Simple php website used to manage the 2019 Georgia Tech Spring Bowling League

Note: This is not perfect, production level software and was created for a specific event. See disclaimer below.

#### Installation

##### Database Setup
- Create a MySQL database with a name and a user with a (secure) password of your choosing
- Run the setup.sql script found in the SQL folder of the created database

##### Website Configuration
- Copy all the files in the 'Frontend' folder into your webroot
- Fill out all the fields in the connection.inc.php & config.inc.php found in the includes folder
- If you are enabling CAS, also fill in all the fields in the cas-config.inc.php file
- Note that there is no user registration feature on the frontend. You will need to create the initial admin user directly in the database and then add other accounts via the admin web interface

#### Disclaimer

```
This software is provided by me "as is" and "with all faults."
I makes no representations or warranties of any kind concerning the
safety, suitability, lack of viruses, inaccuracies, typographical errors,
or other harmful components of this software. There are inherent
dangers in the use of any software, and you are solely responsible for
determining whether this software is compatible with your equipment and
other software installed on your equipment. You are also solely responsible
for the protection of your equipment and backup of your data, and I will
not be liable for any damages you may suffer in connection with using,
modifying, or distributing this software.
```