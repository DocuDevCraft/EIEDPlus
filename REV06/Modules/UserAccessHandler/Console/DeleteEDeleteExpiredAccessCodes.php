<?php

namespace Modules\UserAccessHandler\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\UserAccessHandler\Entities\UserAccessHandler;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DeleteEDeleteExpiredAccessCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'access-codes:cleanup';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'حذف کدهای منقضی‌شده از جدول Access Code';

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
        $expiredCount = UserAccessHandler::where('expire_at', '<', Carbon::now())->delete();

        $this->info("تعداد {$expiredCount} کد منقضی‌شده حذف شد.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
