<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
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
        DB::listen(function ($query) {
            $logInfo = '';
            $logInfo .= 'SQL: ' . $query->sql . PHP_EOL;
            $logInfo .= 'BINDING: ' . json_encode($query->bindings) . PHP_EOL;
            $logInfo .= 'TIME: ' . $query->time . PHP_EOL . PHP_EOL . PHP_EOL;

            info($logInfo);
        });

        Builder::macro('whereLike', function ($attributes, string $searchQuery) {
            $this->where(function ($query) use ($attributes, $searchQuery) {
                foreach (($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchQuery) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchQuery) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchQuery}%");
                            });
                        },
                        function ($query) use ($attribute, $searchQuery) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchQuery}%");
                        }
                    );
                }
            });

            return $this;
        });
    }

}
