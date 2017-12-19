Demos@HOME
==========

This is the code for a web platform to create a social currency network based on monedademos but without salary or taxes, is intended to be used inside an organization, lets say school.

You want to start with your currency? Please let us know at contacto@monedademos.es. Anyway you will have to:

· Put this all in a server (you will also need Yii Framework Version 1.1.12 vailable here: https://github.com/yiisoft/yii/releases/tag/1.1.12 in a sibling "Yii" folder).  
· Set up a database with the sql at /protected/data/rbu.sql
· Configure (at least) your database at /protected/configure/ main.php, console.php (use the corresponding *_example.php file as a guide).
· Make sure both folders assets/ and protected/runtime are writable by web server.

At this point you will need to touch the database yourself to make things fit your needs so:
· Available money for the Fund account go to database table rbu_account, find the fund account and change de credit value with the amount you want x100000 (followed by 5 ceros in default configuration).
· Authorization to fund accounts: to make a user authorized to manage fund account you will need to add a register to rbu_authorization.
· If you need a registration income: go to database table rbu_rule (you can put a rule to begin at the time you want, i.e. first month new users will get Xđ, second month Yđ, make a rule for every month the amount changes). Remember to keep salary as 0 and minimun_salary as the amount you want to give x100000 (followed by 5 ceros in default configuration).


There are many thing to do. Would you join us?

Plenty of doubts, I imagine, ok, contact us.
