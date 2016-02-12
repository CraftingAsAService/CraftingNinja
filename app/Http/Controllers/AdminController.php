<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

class AdminController extends Controller
{

    public function index()
    {
    	return view('admin.index');
    }

    public function users(Request $request)
    {
    	$users = User::with('valid_advanced_crafter_entries')
	    	->where('username', 'like', '%' . $request->get('username', '') . '%')
	    	->orderBy('id')
	    	->simplePaginate(10);
    	return view('admin.users', compact('users'));
    }

}
