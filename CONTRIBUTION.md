# ToDo&Co contribution guidelines

Thank you for being part of the team and contributing to the project! This short guideline will help you to understand how you can contribute to the project. 

<h2>Step 1 : Fork it!</h2>
<li>Navigate to <a href="https://github.com/JENNYPCHEN/p8_to_do_and_co">the project</a></li>
<li>Click <b>Fork</b> ( It is located on the top right hand corner of the page on big screen) </li>

![fork](https://docs.github.com/assets/cb-6294/images/help/repository/fork_button.jpg)

<h2>Step 2 : Clone it! </h2>
<li>Clone and install the project on your local machine. Please follow the instructions provided in<a href="https://github.com/JENNYPCHEN/p8_to_do_and_co#readme"> README.md</a> </li><br>

<h2> Step 3 : Configure Git !</h2>
When you fork a project in order to propose changes to the original repository, you can configure Git to pull changes from the original, or upstream, repository into the local clone of your fork.
<li> Open <b>Git Bash </b>and check the current configured remote repository for your fork by typing <b> git remove -v</b>, you should see </li><br>

``` bash
$  git remote -v
> origin  https://github.com/YOUR_USERNAME/p8_to_do_and_co.git (fetch)
> origin  https://github.com/YOUR_USERNAME/p8_to_do_and_co.git (push)
```
<li> Type </li>

``` bash
$ git remote add upstream https://github.com/JENNYPCHEN/p8_to_do_and_co.git
```
<li> You can verify the new upstream repository you've specified for your fork, by typing <b>git remote -v</b> again. You should see the URL for your fork as origin, and the URL for the original repository as upstream.

``` bash
$  git remote -v
> origin    https://github.com/YOUR_USERNAME/YOUR_FORK.git (fetch)
> origin    https://github.com/YOUR_USERNAME/YOUR_FORK.git (push)
> upstream  https://github.com/JENNYPCHEN/p8_to_do_and_co.git (fetch)
> upstream  https://github.com/JENNYPCHEN/p8_to_do_and_co.git (push)
```

<h2>Step 4 : Change it!</h2>
<li>Here you go! Play around with the project and make it better ! </li>
<li> For testings, you can first setting it up with the instruction writen in <a href="https://github.com/JENNYPCHEN/p8_to_do_and_co#readme">README.md</a> , then run the following command 

``` bash
$  php ./vendor/bin/phpunit 
```
(The above command is for windows users. It may be slightly different for other operation systems)<br>

<h2> Step 5: pull it !</h2>
<li> It is always a good practise to create <b>branches</b>, which allow you to build new features or test out ideas without putting the main project at risk.
<li> When you are ready to propose changes to the project,  head on over to the repository on GitHub where your project lives. It would be at  https://www.github.com/ <'your_username'>/p8_to_do_and_co . You'll see a banner indicating that your branch is one commit ahead of <b>JENNYPCHEN:main</b>. Click Contribute and then Open a pull request.
<li> Pull Requests are also an area for discussion. Please do leave a <b>message</b> explain what you have changed when sending a pull request. If I do not merge it / do not reply to any pull requests, please do not feel offended, I may be just too busy for other projects, do give me some time!</li><br>

<h3>Thanks again for your contribution!
Happy Coding!</h3>



