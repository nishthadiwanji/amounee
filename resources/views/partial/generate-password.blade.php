<div class="modal fade" id="generatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="generatePasswordArea">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="generatePasswordArea">
                    @lang('heading.generate_password') for <span class="m--font-brand" id="username" style="word-break: break-all;"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="m-form m-form--fit m-form--label-align-right" id="generatePasswordForm" name="generatePasswordForm" data-action="" onsubmit="return false;">
                <div class="modal-body">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group">
                            <label class="form-control-label" for="password">@lang('labels.password')</label>
                            <span class="required">*</span>
                            <input type="password" name="password" id="password" class="form-control " placeholder="@lang('placeholders.new_password')" autofocus>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="form-control-label" for="password_confirmation">@lang('labels.confirm_password')</label>
                            <span class="required">*</span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control " placeholder="@lang('placeholders.confirm_new_password')">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success m-btn--air pin-submit" id="generatePasswordBtn"> @lang('buttons.save') </button>
                    <button type="button" class="btn btn-danger m-btn--air" data-dismiss="modal"> @lang('buttons.close') </button>
                </div>
            </form>
        </div>
    </div>
</div>