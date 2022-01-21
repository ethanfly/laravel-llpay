<?php

namespace Ethan\LianLianPay;

use Ethan\LianLianPay\Services\LianLianPay;
use Illuminate\Support\ServiceProvider;

class LianLianPayServiceProvider extends ServiceProvider
{
    protected $defer = true; // 延迟加载服务

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 单例绑定服务
        $this->app->singleton('lianlianpay', function ($app) {
            return new LianLianPay($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/lianlianpay.php' => config_path('lianlianpay.php'),
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数 具体参考laravel 文档
        return ['lianlianpay'];
    }
}
