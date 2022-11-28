<?php

namespace Crater\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCreditNotePdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $credit;

    public $deleteExistingFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($credit, $deleteExistingFile = false)
    {
        $this->credit = $credit;
        $this->deleteExistingFile = $deleteExistingFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->credit->generatePDF('credit', $this->credit->credit_number, $this->deleteExistingFile);

        return 0;
    }
}
