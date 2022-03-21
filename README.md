# p8_to_do_and_co
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/31f12f4a1fc642259fe505426b60394b)](https://www.codacy.com/gh/JENNYPCHEN/p8_to_do_and_co/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JENNYPCHEN/p8_to_do_and_co&amp;utm_campaign=Badge_Grade)[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/31f12f4a1fc642259fe505426b60394b)](https://www.codacy.com/gh/JENNYPCHEN/p8_to_do_and_co/dashboard?utm_source=github.com&utm_medium=referral&utm_content=JENNYPCHEN/p8_to_do_and_co&utm_campaign=Badge_Coverage)

Hi there, 
Thank you for your interest in this project. I'm delighted to announce that this is my last project for my PHP/Symfony training with OpenClassrooms (which means I'll then have more time to explore new projects and of course for the job search! wohooo!). Regarding this project, it's about upgrading an old application and testing it using PhpUnit. We also explore how to write a technical documentation as well as a code audit with the help of Blackfire and Codacy.

Please let me know what you think about this project. I'm always open to improvements!

<p align="center">Homepage of the upgraded application (It is a task management application)</p>

<p align="center">Homepage after login </p>

<h2>Features</h2>
<li>Upgrade an application from Symfony 3 to 6 (which is the latest version currently)<br></li>
<li>New functions concerning the autentication are added (user roles etc) <br></li>
<li>Technical documentation has been created to explain how authentication works in the application <br></li>
<li>Code audio has been created to analyse the quality of code and the performance <br></li>

<h2>Prerequisites</h2>
<li>PHP >=8.0.2<br></li>
<li>Symfony > 6.0 <br></li>
<li>Local server, e.g. XAMPP/WAMP for local use.<br></li>
<li>MySQL database management tool, e.g PhpMyAdmin<br></li>
<li>Libraries will be installed using Composer.<br></li>

<h2>Starting the application</h2>
You can run the application on your computer for development and testing purposes by following the simple steps below:<br>

<h3>Installation</h3>
<h4>Step1 Clone / Download</h4>
Clone the repository of this page.

<h4>Step 2 Configure environment variables</h4>
Open .env file and modify line 30 (database URL) with your own detail.

<h4>Step 3 Install all dependencies</h4>
Install Composer if you do not have it yet. </br>
In your cmd, go to the directory where you want to install the project and install dependencies with composer with the commands below:</br>

``` bash
$ cd some\directory
```

``` bash
$ composer install
```
All dependencies should be installed in a vendor directory.

<h4>Step 4 Create database</h4>
Create a new database using the command below:<br>

``` bash
$ php bin/console doctrine:database:create
```

``` bash
$ php bin/console doctrine:migrations:migrate
```
You can import fixture by running the command below:<br>

``` bash
$ php bin/console doctrine:fixtures:load
```
<h4>Step 5 (optional) Configuring database for Tests</h4>
If you want to run the Tests, it is always recommanded to use a seperate database, to do it, you can follow the command below: <br>

``` bash
$ php bin/console --env=test doctrine:database:create
```

``` bash
$ php bin/console --env=test doctrine:schema:create
```
In order to load the fixtures for the test purpose, you can run the command as below:<br>

``` bash
$ php bin/console --env=test doctrine:fixtures:load
```
<h4>Here you are!</h4>
Wohoo! You are ready to go! If you use symfony local server, simply run </br>

``` bash
$ symfony serve: start
```

Have a great day! Looking forwards to sharing more projects/news with you!</br></br>
ChingYi P.C
