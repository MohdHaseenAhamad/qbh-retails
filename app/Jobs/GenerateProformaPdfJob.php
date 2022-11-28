<?php

namespace Crater\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateProformaPdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $proforma;

    public $deleteExistingFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($proforma, $deleteExistingFile = false)
    {
        $this->proforma = $proforma;
        $this->deleteExistingFile = $deleteExistingFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->proforma->generatePDF('proforma', $this->proforma->proforma_number, $this->deleteExistingFile);

        return 0;
    }
}
