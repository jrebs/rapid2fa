@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Two-factor Authencation Setup</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="panel-body">
                        <div style="text-align: center;">
                            @if ($image)
                                <div class="alert alert-success" role="alert">
                                    Scan the QR code using a standard two-factor
                                    auth app such as Google Authenticator:
                                </div>
                                <br />

                                <div>
                                    <img alt="Image of QR barcode" src="{{ $image }}" />
                                </div>
                            @elseif (app()->isLocal())
                                <div class="alert alert-danger" role="alert">
                                    You are probably missing the <b>php-imagick</b> extension.
                                </div>
                            @endif

                            <div style="text-align: center;">
                                If you're unable to use QR barcodes or you do
                                not see one above, you can enter the following
                                code into your app:<br/>
                                <code style="color: rgb(122, 9, 9); font-size: 40px;">{{ $secret }}</code>
                            </div>

                            <div class="alert alert-success" role="alert">
                                Next, input the current authenticator code to confirm.
                            </div>

                            <form method="POST" action="{{ route('rapid2fa.confirm') }}">
                                @csrf

                                <input type="hidden" name="token" value="{{ $secret }}"/>

                                <input id="rapid2fa" type="text" class="form-control" name="rapid2fa" style="text-align: center; width: 30%; margin: auto;" autofocus>
                                <br/>

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Confirm') }}
                                </button>

                            </form>

                            <br /><br />

                            <a href="{{ url('/home') }}">Go Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
