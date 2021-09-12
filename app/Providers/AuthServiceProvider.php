<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Подтверждение электронной почты')
                ->line('Вы зарегистрировались на сайте AMS Discount.')
                ->line('Для подтверждения электронной почты нажмите на кнопку.')
                ->action('Подтвердить почту', $url)
                ->line('Если Вы не создавали аккаунт на сайте AMS Discount, то просто проигнорируйте данное письмо.');
        });

        ResetPassword::toMailUsing(function ($notifiable, $url) {
            $url = url('reset-password').'/'.$url.'?email='.$notifiable->getEmailForPasswordReset();
            return (new MailMessage)
                ->subject('Сброс пароля на сайте AMS Discount')
                ->line('Вы получили это письмо, потому что на сайте AMS Discount был получен запрос на сброс пароля для этого аккаунта.')
                ->action('Сброс пароля', $url)
                ->line('Эта ссылка действительна в течении 1 часа.')
                ->line('Если Вы не запрашивали сброс пароля, то просто проигнорируйте данное письмо.');
        });
    }
}
