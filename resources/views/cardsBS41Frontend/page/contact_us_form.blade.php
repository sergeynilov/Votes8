
                    <!-- Page Content : contact us -->
                    <div id="page-wrapper" class="card">

                        <div class="card-body">

                            <form method="POST" action="{{ URL::route('contact-us-post') }}" accept-charset="UTF-8" id="form_contact_us" enctype="multipart/form-data">
                                {!! csrf_field() !!}


                                @include($frontend_template_name.'.layouts.page_header')

                                <div class="form-row mb-3 {{ in_array('author_name', $errorFieldsArray) ? 'validation_error' : '' }}">
                                    <label class="col-12 col-sm-4 col-form-label" for="author_name">Name<span class="required"> * </span></label>
                                    <div class="col-12 col-sm-8">
                                        {!! $viewFuncs->text('author_name', !empty(old('author_name'))? Purifier::clean(old('author_name') ): '', "form-control editable_field", [] ) !!}
                                    </div>
                                </div>

                                <div class="form-row mb-3 {{ in_array('author_email', $errorFieldsArray) ? 'validation_error' : '' }}">
                                    <label class="col-12 col-sm-4 col-form-label" for="author_email">Email<span class="required"> * </span></label>
                                    <div class="col-12 col-sm-8">
                                        {!! $viewFuncs->text('author_email', !empty(old('author_email')) ? Purifier::clean(old('author_email')): '', "form-control editable_field", [] ) !!}
                                    </div>
                                </div>

                                <div class="form-row mb-3 {{ in_array('message', $errorFieldsArray) ? 'validation_error' : '' }}">
                                    <label class="col-12 col-sm-4 col-form-label" for="message">Message<span class="required"> * </span></label>
                                    <div class="col-12 col-sm-8">
                                        {!! $viewFuncs->textarea('message', !empty(old('message'))? Purifier::clean(old('message')): '', "form-control editable_field", [ "rows"=>"10", "cols"=> 120, "autocomplete"=>"off"  ] ) !!}
                                    </div>
                                </div>

                                @if(!empty($account_contacts_us_text))
                                    <div class="form-row mb-3">
                                        <fieldset class="notes-block text-muted">
                                            <legend class="notes-blocks">Notes</legend>
                                            {!! $account_contacts_us_text !!}
                                        </fieldset>
                                    </div>
                                @endif


                                <div class="form-row mb-3 {{ in_array('captcha', $errorFieldsArray) ? 'validation_error' : '' }}">
                                    <label class="col-4 col-sm-4 col-form-label" for="captcha">Captcha</label>
                                    <div class="col-4 col-sm-4">
                                        {!! $viewFuncs->text('captcha', '', "form-control editable_field", [ "maxlength"=>"6", "autocomplete"=>"off"  ] ) !!}
                                    </div>
                                    <div class="col-4 col-sm-4">
                                        {!! captcha_img('flat') !!}
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-6 col-sm-6">
                                        <button type="button" class="btn btn-inverse" onclick="javascript:document.location='{{ URL::route('home') }}'"
                                                style=""><span class="btn-label"></span> &nbsp;Cancel
                                        </button>&nbsp;&nbsp;
                                    </div>

                                    <div class="col-6 col-sm-6">
                                        <div class="btn-group float-right mt-2" role="group">
                                            <button type="submit" class="btn btn-primary right"><span class="btn-label"></span> &nbsp;Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>


                        </div> <!-- class="card-body" -->


                    </div>
                    <!-- /.page-wrapper Page Content : contact us -->
