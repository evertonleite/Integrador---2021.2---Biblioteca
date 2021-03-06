<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Exception;

class AccountController extends Controller
{
    	### Sign In
	/* After submitting the sign-in form */
	public function postSignIn(Request $request) {
		$validator = $request->validate([
				'username' 	=> 'required',
				'password'	=> 'required'

		]);
		if(!$validator) {
			// Redirect to the sign in page
			return Redirect::route('account-sign-in')
				->withErrors($validator)
				->withInput();   // redirect the input

		} else {

			$remember = ($request->has('remember')) ? true : false;
			$auth = Auth::attempt(array(
				'username' => $request->get('username'),
				'password' => $request->get('password')
			), $remember);
		} 

		if($auth) {
			
			return Redirect::intended('home');

		} else {
			
			return Redirect::route('account-sign-in')
				->with('global', 'Senha ou email incorretos.');
		}

		return Redirect::route('account-sign-in')
			->with('global', 'There is a problem. Have you activated your account?');
	}

	/* Submitting the Create User form (POST) */
	public function postCreate(Request $request) {
		// dd($request->all());
		$validator = $request->validate([
				'email' => 'required',
				'name' => 'required',
				'username'		=> 'required|max:20|min:3|unique:users',
				'password'		=> 'required',
				'password_again'=> 'required|same:password'
		]);

		if(!$validator) {
			return Redirect::route('account-create')
				->withErrors($validator)
				->withInput();   // fills the field with the old inputs what were correct

		} else {
			// create an account
			$username	= $request->get('username');
			$password 	= $request->get('password');
			$name 	= $request->get('name');
			$email 	= $request->get('email');

			$userdata = User::create([
				'name' => $name,
				'email' => $email,
				'username' 	=> $username,
				'password' 	=> Hash::make($password)	// Changed the default column for Password
			]);

			if($userdata) {			


				return Redirect::route('account-sign-in');//->with('global', 'Your account has been created. We have sent you an email to activate your account');				
			}
		}
	}

	public function getSignIn() {
		return view('account.signin');
	}

	/* Viewing the form (GET) */
	public function getCreate() {
		
		return view('account.create');
	}

	### Sign Out
	public function getSignOut() {
		Auth::logout();
		return Redirect::route('account-sign-in');
	}

	public function alluser(){
		$this->authorize('is_admin');
		$users = User::all();
		return view('panel.alluser',['users'=>$users]);
	}

	public function edit($id){
		$users=User::findOrFail($id);
		//return $users;
		return view('panel.edituser',compact('users'));
	}

	public function update(request $request, $id){

		$users=User::findOrFail($id);
		$users->username=$request->input('username');
		$users->email=$request->input('email');
		$users->password=Hash::make($request['password']);
		$users->name=$request->input('name');
		$users->profile=$request->input('profile');
		$users->save();
		return redirect()->route('alluser');
	}

	public function destroy($id){

		$users = User::findOrFail($id);
		$users->delete();

		return redirect()->route('alluser');
	}
}
