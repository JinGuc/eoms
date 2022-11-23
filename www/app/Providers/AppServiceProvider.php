<?php

namespace App\Providers;

use App\Servers\DbLog\DbLog;
use App\Servers\WebSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $WebSettingArr = WebSetting::all();
            Config::set("app.name", $WebSettingArr["web_title"]);
            Config::set("app.copyright", $WebSettingArr["copy_right"]);
            Config::set("mail.MAIL_MAILER", $WebSettingArr["mail_matler"]);
            Config::set("mail.mailers.smtp.host", $WebSettingArr["mail_host"]);
            Config::set("mail.mailers.smtp.port", $WebSettingArr["mail_port"]);
            Config::set("mail.mailers.smtp.encryption", $WebSettingArr["mail_encryption"]);
            Config::set("mail.mailers.smtp.username", $WebSettingArr["mail_username"]);
            Config::set("mail.mailers.smtp.password", $WebSettingArr["mail_password"]);
            Config::set("mail.from.name", $WebSettingArr["mail_from_name"]);
            Config::set("mail.from.address", $WebSettingArr["mail_from_adders"]);
        }catch(\Exception $e){

        }
        /**
         * 设置默认长度191
         */
        Schema::defaultStringLength(191);
        DbLog::run();
    }
}
