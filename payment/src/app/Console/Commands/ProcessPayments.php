<?php

namespace App\Console\Commands;

use App\Services\PaymentService\PaymentService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ProcessPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:process {clientId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process payments';

    /**
     * @var PaymentService
     */
    protected $paymentService;

    /**
     * Create a new command instance.
     * @param PaymentService $paymentService
     * @return void
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Collection $payments */
        $payments = $this->paymentService->getConfirmedClientPayment($this->argument('clientId'));
        /** @var array $approvedPayments */
        $approvedPayments = $this->paymentService->processClientPayments(
            $payments->pluck('id')->toArray()
        );

        $this->info('Processing is started...');

        if (count($approvedPayments) > 0) {
            $this->info(json_encode($approvedPayments, JSON_PRETTY_PRINT));
        } else {
            $this->info('No payments to process');
        }

        $this->info('Processing complete');
    }
}
