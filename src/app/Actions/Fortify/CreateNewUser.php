<?php

namespace App\Actions\Fortify;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Log;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected $request;

    public function __construct(RegisterRequest $request)
    {
        $this->request = $request;

        Log::info('Validation class initialized', [
            'request_class' => get_class($request),
            'validation_rules' => $request->rules()
        ]);
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
    {
        Log::info('CreateNewUser initialization', [
            'request_class' => get_class($this->request),
            'is_form_request' => $this->request instanceof \Illuminate\Foundation\Http\FormRequest
        ]);

        Log::info('Starting validation process', [
            'input_data' => $input,
            'using_validation_rules' => $this->request->rules(),
            'using_custom_messages' => $this->request->messages()
        ]);

        try {
            Log::info('Attempting validation');

            $validated = $this->request->validated();

            Log::info('Validation successful', [
                'validation_class_used' => get_class($this->request),
                'validated_data' => $validated,
                'validation_rules_applied' => $this->request->rules()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'validation_class_used' => get_class($this->request),
                'validation_errors' => $e->errors(),
                'attempted_data' => $input,
                'rules_that_failed' => $this->request->rules()
            ]);
            throw $e;
        }

        Log::info('Creating user after successful validation');

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        try {
            Log::channel('mail')->info('メール送信開始', ['user_id' => $user->id]);
            $user->sendEmailVerificationNotification();
            Log::channel('mail')->info('メール送信成功', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::channel('mail')->error('メール送信エラー', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $user;
    }
}
