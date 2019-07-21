<div class="panel panel-default">
    <div class="panel-heading">Two-Factor Authentication</div>

    <div class="panel-body">
        @if (Auth::user()->google2fa_secret)
        <a href="{{ route('rapid2fa.disable') }}" class="btn btn-warning">Disable 2FA</a>
        @else
        <a href="{{ route('rapid2fa.enable') }}" class="btn btn-primary">Enable 2FA</a>
        @endif
    </div>
</div>
