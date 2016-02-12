<?php

// TODO, Convert text to language files

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\AdvancedCrafter;

use Log;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('account.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('account.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Test their current password
        if ( ! Auth::attempt(['email' => Auth::user()->email, 'password' => $request->get('current_password')]))
        {
            flash()->error('Your current password did not match');
            return redirect()->back()->withInput();
        }

        // Test their new password
        if ($request->get('password'))
        {
            if ($request->get('password') != $request->get('password_confirmation'))
            {
                flash()->error('Your new password does not match the confirmation password');
                return redirect()->back()->withInput();
            }

            // Change their password
            Auth::user()->password = bcrypt($request->get('password'));
        }

        // Update their username and email
        Auth::user()->username = $request->get('username');
        Auth::user()->email = $request->get('email');

        Auth::user()->save();

        flash()->success('You have modified your information');
        return redirect('/account');
    }

    /**
     * Handle a subscription
     */
    public function checkout(Request $request)
    {
        // A month for every two bucks
        $token = $request->get('token');
        $amount = (int) $request->get('final-amount');

        if ($amount <= 0)
        {
            flash()->error('Donating zero dollars makes baby kittens cry.');
            return redirect('/account');
        }

        try {
            // Set Stripes Secret Key
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            // Charge the user
            $charge = \Stripe\Charge::create(array(
                'amount' => $amount * 100,
                'currency' => 'usd',
                'source' => $token, // obtained with Stripe.js,
                'description' => Auth::user()->email,
            ));

            // Determine length of time to grant advanced crafter status
            $valid_until = AdvancedCrafter::calculate_length($amount);

            // Add the Advanced Crafter entry to the table
            $advanced_crafter = new AdvancedCrafter([
                'amount' => $amount,
                'stripe_transaction_id' => $charge->id,
                'valid_until' => $valid_until
            ]);

            // Force a refresh on if they're advanced or not (which, they are at this point)
            Auth::user()->is_advanced_crafter(true);

            Auth::user()->advanced_crafter_entries()->save($advanced_crafter);

            flash()->success('Enjoy being an Advanced Crafter!  We appreciate your continued support!');

        } catch(\Stripe\Error\Card $e) {
            // Card decline
            $this->stripe_error_handler($e, $e->getMessage());
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $this->stripe_error_handler($e, $e->getMessage());
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $this->stripe_error_handler($e, 'Invalid Request');
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $this->stripe_error_handler($e, $e->getMessage());
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $this->stripe_error_handler($e, $e->getMessage());
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $this->stripe_error_handler($e, $e->getMessage());
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->stripe_error_handler($e, $e->getMessage());
        }

        return redirect('/account');
    }

    /**
     * A common error handler for Stripe errors to Log and Flash
     * @param  object $exception
     * @param  string $output_to_user
     */
    private function stripe_error_handler($exception, $output_to_user)
    {
        Log::error('Stripe Error: ' . $exception->getMessage(), ['user' => Auth::user()->email, 'exception' => $exception]);
        flash()->error('Stripe Error: <strong>' . $output_to_user . '</strong> &mdash; Sorry for the inconvenience.  Your payment was not processed.  Please try again later or <a href="/contact">contact us</a>.');
    }
}
