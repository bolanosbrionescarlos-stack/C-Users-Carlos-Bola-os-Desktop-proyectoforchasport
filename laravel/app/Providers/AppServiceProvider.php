<?php

namespace App\Providers;

use App\Services\CartService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(CartService::class);
    }

    public function boot(): void
    {
        Blade::directive('money', function (string $expression): string {
            return "<?php echo 'S/ '.number_format((float) ({$expression}), 2, '.', ','); ?>";
        });
    }
}
