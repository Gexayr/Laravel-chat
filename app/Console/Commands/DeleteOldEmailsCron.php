<?php

namespace App\Console\Commands;

use App\Emails;
use Illuminate\Console\Command;

class DeleteOldEmailsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-old-emails:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $oldEmailsGrouped = array_chunk($this->getOldEmails(), 1000);
        foreach ($oldEmailsGrouped as $oldEmailIds) {
            $this->removeOldEmails($oldEmailIds);
        }
    }


    private function getOldEmails(): array
    {
        return Emails::where('created_at', '<',  date('Y-m-d H:i:s', strtotime('-24 hours')))
            ->pluck('id')
            ->toArray();

    }

    private function removeOldEmails(array $oldEmailIds)
    {
        Emails::query()
            ->whereIn('id', $oldEmailIds)
            ->delete();
    }
}
