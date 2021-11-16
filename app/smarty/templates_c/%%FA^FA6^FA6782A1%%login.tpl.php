<?php /* Smarty version 2.6.32, created on 2021-11-16 03:13:01
         compiled from users/login.tpl */ ?>
<link href="public/css/login.css" rel="stylesheet" crossorigin="anonymous" />
<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->
        <div class="fadeIn first">
        <br>
        <?php if (isset ( $this->_tpl_vars['status'] ) && $this->_tpl_vars['status'] == false): ?>
            <div class="dannger">Username or password is incorrect</div>
        <?php endif; ?>
        </div>
        <br> <br>
        <!-- Login Form -->
        <form action="login" method="POST">
            <input type="text" id="username" class="fadeIn second" name="username" placeholder="username">
            <input type="password" id="password" class="fadeIn third" name="password" placeholder="password">
            <input type="submit" class="fadeIn fourth" value="Log In">
        </form>

    </div>
</div>