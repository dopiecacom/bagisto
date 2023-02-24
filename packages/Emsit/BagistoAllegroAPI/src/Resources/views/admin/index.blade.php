@extends('admin::layouts.master')

@section('page_title')
    Allegro API
@stop

@section('content-wrapper')

    <div class="content full-page dashboard">
        <form method="POST" action="{{ route('admin.bagistoallegroapi.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="page-header">
                <div class="page-title">
                    <h1>Allegro API</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        Save
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    <div class="accordian active">
                        <div class="accordian-header">
                            Basic Settings
                        </div>
                        <div class="accordian-content">
                            <div>
                                <div class="control-group text">
                                    <label for="client_id">Client ID</label>
                                    <input type="text"
                                           id="client_id"
                                           name="client_id"
                                           data-vv-as="&quot;Client ID&quot;"
                                           value="{{ $data['client_id'] }}"
                                           class="control"
                                           aria-required="true"
                                           required>
                                </div>
                                <div class="control-group text">
                                    <label for="client_secret">Client Secret</label>
                                    <input type="text"
                                           id="client_secret"
                                           name="client_secret"
                                           value="{{ $data['client_secret'] }}"
                                           data-vv-as="&quot;Client Secret&quot;"
                                           class="control"
                                           aria-required="true"
                                           required>
                                </div>
                                <div class="control-group boolean">
                                    <label for="sandbox_mode">Sandbox Mode</label>
                                    <label class="switch">
                                        <input type="hidden" name="sandbox_mode"
                                               value="0">
                                        <input type="checkbox"
                                               id="sandbox_mode"
                                               name="sandbox_mode"
                                               value="1"
                                              {!! $data['sandbox_mode'] == 1  ? 'checked="checked"' : null !!}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ $data['authUri'] }}" target="_blank">Zaloguj do Allegro</a>
            </div>
        </form>
    </div>

@stop