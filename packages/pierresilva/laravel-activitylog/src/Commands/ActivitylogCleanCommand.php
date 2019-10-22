<?php

namespace pierresilva\Activitylog\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use pierresilva\Activitylog\ActivitylogServiceProvider;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Eloquent\Builder;

class ActivitylogCleanCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'activitylog:clean
                            {log? : (optional) The log name that will be cleaned.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old registers from activity log.';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'activitylog:clean';

    protected $older_records;

    public function __construct()
    {
        parent::__construct();
        $this->older_records = config('activitylog.delete_records_older_than_days');
    }

    public function handle()
    {
        $this->comment('Cleaning activity log...');

        $log = $this->argument('log');

        $maxAgeInDays = $this->older_records;

        $cutOffDate = Carbon::now()->subDays($maxAgeInDays)->format('Y-m-d H:i:s');

        $activity = ActivitylogServiceProvider::getActivityModelInstance();

        $amountDeleted = $activity::where('created_at', '<', $cutOffDate)
            ->when($log !== null, function (Builder $query) use ($log) {
                $query->inLog($log);
            })
            ->delete();

        $this->info("Deleted {$amountDeleted} record(s) from the activity log.");

        $this->comment('All done!');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['log', InputArgument::OPTIONAL, 'Activity Log to clean.'],
        ];
    }
}
