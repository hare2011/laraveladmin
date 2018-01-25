<?php

namespace Runhare\Admin\Commands;


use Illuminate\Console\Command;
/**
 * Description of RouteCommand
 *
 * @author hare
 * @Time   Jan 24, 2018 5:13:47 PM
 */
class RouteCommand extends Command {
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成Laravel Admin route';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       
       $comment = $this->ask('give the route description');       
       $para = [
           $this->ask('The route namespace.'),$this->ask('the page name.')
       ];       
       
       $controller = ucfirst($para[1]);
       $para[1] = strtolower($para[1]);
       echo <<<EOT
        
        //$comment
        Route::get('/$para[0]/$para[1]', '{$controller}Controller@index')->name('$para[0].$para[1].index');
        Route::get('/$para[0]/$para[1]/{id}/edit', '{$controller}Controller@edit')->name('$para[0].$para[1].edit');
        Route::get('/$para[0]/$para[1]/create', '{$controller}Controller@create')->name('$para[0].$para[1].create');
        Route::post('/$para[0]/$para[1]', '{$controller}Controller@store')->name('$para[0].$para[1].store');
        Route::delete('/$para[0]/$para[1]/{id}', '{$controller}Controller@destroy')->name('$para[0].$para[1].destroy');
        Route::match(['put', 'patch'], '/$para[0]/$para[1]/{id}', '{$controller}Controller@update')->name('$para[0].$para[1].update');

                               
EOT;
      
    }
}
