<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        View::composer('*', function ($view){
            //category links used in the homepage header
            $categoryLinks = Category::all();
            $view->with('categoryLinks', $categoryLinks);

               // FOR ALL PRODUCTS
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        $view->with('products', $products);

        });


     
    }
}
