<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class AdvancedCrafter extends Model
{

    protected $fillable = [
        'user_id', 'amount', 'stripe_transaction_id', 'valid_until',
    ];

	protected $dates = ['valid_until'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function calculate_length($amount)
    {
    	// Every $22 gets a year
    	// Every $2 gets a month
    	// $24 would get 1 year 1 month, $20 would get 10 months
    	$years_worth = 22;
    	$months_worth = 2;

    	$years = floor($amount / $years_worth);
    	$months = $years * 12;
    	$remaining_amount = $amount - (22 * $years);

    	$months += floor($remaining_amount / $months_worth);

    	return Carbon::now()->addMonths($months);
    }

}
