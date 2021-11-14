<!--begin add Modal -->
<div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> Create Courses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate id="add_form" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="csrf_token()" />
                    <input type="hidden" name="token" value="{$token}" id="csrf_token" />

                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="small mb-1" for="name"> Courses Name </label>
                                <input class="form-control py-4" id="name" type="text" placeholder=" Enter name "
                                    name="name" required />
                                <div class="invalid-feedback">
                                    Valid name is required
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                        <button type="submit" class="btn btn-primary" id="create_courses"> Create </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- end Modal-->