<?php /* Smarty version 2.6.32, created on 2021-11-18 02:08:10
         compiled from student/updateStudent.tpl */ ?>
<!--begin update Modal -->
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Update Student </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="needs-validation" novalidate id="update_form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="csrf_token()" />
                    <input type="hidden" name="token" value="<?php echo $this->_tpl_vars['token']; ?>
" id="csrf_token" />

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small mb-1" for="update_name"> Name </label>
                                <input class="form-control py-4" id="update_name" type="text" placeholder=" Enter name "
                                    name="name" required />
                                <div class="invalid-feedback">
                                    Valid name is required
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small mb-1" for="update_time"> Time </label>
                                <input class="form-control py-4" id="update_time" placeholder=" Enter time " name="time"
                                    type="datetime-local" required />
                                <div class="invalid-feedback">
                                    Valid time is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small mb-1" for="update_courses"> Courses </label>
                                <select class="form-control" id="update_courses" name="type" required>
                                    <option value="">Select courses</option>
                                    <?php $_from = $this->_tpl_vars['courses']; if (($_from instanceof StdClass) || (!is_array($_from) && !is_object($_from))) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
                                        <option value="<?php echo $this->_tpl_vars['item'][0]; ?>
"><?php echo $this->_tpl_vars['item'][1]; ?>
</option>
                                    <?php endforeach; endif; unset($_from); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="small mb-1" for="update_score"> Score </label>
                                <input class="form-control py-4" id="update_score" placeholder=" Enter score " name="score"
                                    required />
                                <div class="invalid-feedback">
                                    Valid score is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                        <button type="submit" class="btn btn-primary" id="update_student"> Update </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- end Modal-->