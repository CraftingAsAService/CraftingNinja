@extends('app')

@section('meta')
    <meta name='stripe-token' content='{{ config('services.stripe.key') }}'>
@endsection

@section('banner')
    <a href="/logout" class='pull-sm-right btn btn-secondary'>
        Logout <i class="fa fa-sign-out"></i>
    </a>
    <h1>Your Account</h1>
@endsection

@section('content')

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="card">
            <h3 class='card-header'>
                Hello {{ Auth::user()->username }}!
            </h3>
            <div class="card-block">
                <a href='/account/edit' class='btn btn-primary'>Edit Account</a>
            </div>
        </div>

        <a name='donate'></a>
        @if (Auth::user()->advanced)
        <div class='card'>
            <h3 class='card-header'>
                Thank you for your support!
            </h3>
            <div class='card-block'>
                <p>
                    We really appreciate your donation!
                </p>
                <p>
                    Your advanced crafter status is valid for another <em>{{ \Carbon\Carbon::createFromTimestamp(Auth::user()->advanced)->diffInDays() }}</em> days!
                </p>
            </div>
        </div>
        @else
        <div class='card'>
            <h3 class='card-header'>
                Become an Advanced Crafter today!
            </h3>
            <div class='card-block'>
                <div class='row'>
                    <div class='col-sm-6'>
                        <p>
                            <strong>Benefits Include</strong>
                        </p>
                        <ul>
                            <li>No Ads!</li>
                            <li>Saved Carts</li>
                            <li>Page Progress Saving</li>
                            <li>and More!</li>
                        </ul>

                        <p>
                            <strong>Why you should Donate</strong>
                        </p>
                        <ul>
                            <li>Ads are annoying</li>
                            <li>You love the site</li>
                            <li>Servers are expensive</li>
                        </ul>
                    </div>
                    <div class='col-sm-6'>
                        <div class='card text-xs-center'>
                            <div class='card-block'>
                                {!! Form::open(['url' => '/account/checkout', 'class' => 'form-inline', 'id' => 'checkout-form']) !!}
                                <input type='hidden' name='token' value=''>
                                <input type='hidden' name='email' value='{{ Auth::user()->email }}'>
                                <input type='hidden' name='final-amount' value='0'>
                                <div class='row'>
                                    <div class='col-xs-12 col-sm-7'>
                                        <div class='form-group'>
                                            <label class='sr-only' for='amount'>Amount (in dollars)</label>
                                            <div class='input-group'>
                                                <div class='input-group-addon'>$</div>
                                                {!! Form::text('amount', '22', ['placeholder' => 'Amount', 'class' => 'mobile-tel-convert text-xs-right']) !!}
                                                <div class='input-group-addon'>.00</div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class='col-xs-12 col-sm-5'>
                                        <button class='btn btn-primary btn-block checkout'>Donate</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                            <div class='card-footer text-muted'>
                                Donate the suggested amount of $22 and you will receive a year of the Advanced Crafter status!  Otherwise receive a month for every $2 in donations.
                            </div>
                        </div>

                        <div class='text-xs-center'>
                            <a href='https://stripe.com/'><img src='/images/vendors/stripe@2x.png' height='41'></a>
                            <p>
                                <i class='fa fa-cc-visa'></i>
                                <i class='fa fa-cc-mastercard'></i>
                                <i class='fa fa-cc-amex'></i>
                                <i class='fa fa-cc-jcb'></i>
                                <i class='fa fa-cc-discover'></i>
                                <i class='fa fa-cc-diners-club'></i>
                                <i class='fa fa-btc'></i>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('javascript')
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script src="/js/account/checkout.js"></script>
@endsection
