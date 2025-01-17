<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required'],
            'email' => ['required','email'],
            'password' => ['required','min:8'],
            'password-confirmation' => ['required','min:8','same:password']
            // 'name' => ['required', 'string', 'max:255'],
            // 'email' => [
            //     'required',
            //     'string',
            //     'email',
            //     'max:255',
            //     Rule::unique(User::class),
            // ],
            // 'password' => $this->passwordRules(),
        ], [
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password-confirmation.required' => 'パスワードを入力してください',
            'password-confirmation.min' => 'パスワードは8文字以上で入力してください',
            'password-confirmation.same' => 'パスワードと一致しません',
        ])->validate();

        // app(RegisterRequest::class)->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

    }
}
