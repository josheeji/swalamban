<?php

namespace App\Console\Commands;

use App\Http\Controllers\ForexController;
use App\Models\Forex;
use Illuminate\Console\Command;

class ForexSaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forex:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $forexController;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ForexController $forexController)
    {
        parent::__construct();
        $this->forexController = $forexController;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // $this->forexController->save();

        $ch = curl_init();
        print_r(url('forex-save'));
        // set url
        // curl_setopt($ch, CURLOPT_URL, 'https://online-account.kumaribank.com:444/kumari-web/forex-save');
        curl_setopt($ch, CURLOPT_URL, 'https://kumaribank.com/forex-save');
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        $this->info('Forex updated successfully');

    }
}
