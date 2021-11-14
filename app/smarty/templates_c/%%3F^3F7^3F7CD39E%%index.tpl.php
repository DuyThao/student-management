<?php /* Smarty version 2.6.32, created on 2021-11-12 08:14:48
         compiled from demo/index.tpl */ ?>
<div class="card mb-4">
    <form action="search-item" method="POST" >
        <input class="form-control py-4" id="search_test" name="search_test" type="text" />
        <div class="" id="search_result"> <br><input class="" type="submit" value="Search" />
        </div>

    </form>
</div>
<pre>
</pre>
<?php $_from = $this->_tpl_vars['list_data']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
    <ul>
        <li> <?php echo $this->_tpl_vars['i'][1]; ?>
</li>
        <li> <?php echo $this->_tpl_vars['i'][2]; ?>
</li>
        <li> <?php echo $this->_tpl_vars['i'][3]; ?>
</li>
        <li> <a href="user-detail/<?php echo $this->_tpl_vars['i'][0]; ?>
"> detail </a></li>
    </ul>
<?php endforeach; endif; unset($_from); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "share/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script src="public/js/demo.js"></script>