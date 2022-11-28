<?php

namespace Crater\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePurchasePdfJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $purchase;

    public $deleteExistingFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($purchase, $deleteExistingFile = false)
    {
        $this->purchase = $purchase;
        $this->deleteExistingFile = $deleteExistingFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->purchase->generatePDF('purchase', $this->purchase->purchase_no, $this->deleteExistingFile);

        return 0;
    }
}
