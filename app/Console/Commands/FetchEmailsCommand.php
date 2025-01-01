<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\App; // Correctly import App facade


class FetchEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:fetch-every-10-seconds';
   

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch emails every 10 seconds';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {
            
            App::make(EmailController::class)->fetchEmails();
            sleep(10); // Wait for 10 seconds before fetching again
        }
    }
}
