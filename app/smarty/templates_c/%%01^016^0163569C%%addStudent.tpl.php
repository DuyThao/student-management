<?php /* Smarty version 2.6.32, created on 2021-11-16 11:24:12
         compiled from courses/addStudent.tpl */ ?>
<!--begin add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Assign Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="needs-validation" novalidate id="add_form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="csrf_token()" />
                    <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" id="csrf_token" />

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small mb-1" for="name"> Student </label>
                                <select class="form-control" id="name" name="type" required>
                                    <option value="">Select student</option>
                                    <?php $_from = $this->_tpl_vars['students']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                                        <option value="<?php echo $this->_tpl_vars['item'][0]; ?>
"><?php echo $this->_tpl_vars['item'][1]; ?>
</option>
                                    <?php endforeach; endif; unset($_from); ?>
                                </select>
                                <div class="invalid-feedback">
                                    Valid student is required
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small mb-1" for="score"> Score </label>
                                <input class="form-control py-4" id="score" placeholder=" Enter score " name="score"
                                    required />
                                <div class="invalid-feedback">
                                    Valid score is required
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                        <button type="submit" class="btn btn-primary" id="create_student"> Create </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- end Modal-->