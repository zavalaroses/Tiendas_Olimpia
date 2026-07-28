<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\catalogos\Tiendas;
use App\Models\CierreFinanciero;
use App\Services\BalanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerarSnapshotFinanciero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:financiero {--fecha=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera snapshots financieros diarios';

    /**
     * Execute the console command.
     */
    public function handle(BalanceService $balanceService)
    {
        try {
            DB::beginTransaction();
            $fecha = $this->option('fecha') ? Carbon::parse($this->option('fecha'))->toDateString() : Carbon::yesterday()->toDateString();

            $this->info("Generando snapshot para {$fecha}");

            $tiendas = Tiendas::whereNull('deleted_at')->get();
            foreach ($tiendas as $tienda) {
                $datos = $balanceService->calcular($tienda->id,$fecha);

                CierreFinanciero::updateOrCreate(
                    [
                        'tienda_id' => $tienda->id,
                        'fecha' => $fecha
                    ],
                    [
                        'inventario' => $datos['inventario'],
                        'caja' => $datos['caja'],
                        'bancos' => $datos['bancos'],
                        'apartados' => $datos['apartados'],
                        'saldo_favor' => $datos['saldo_favor'],
                        'adeudos' => $datos['adeudos'],
                        'balance' => $datos['balance'],
                    ]
                );
                $this->info(
                    "Tienda {$tienda->id} procesada. Balance: {$datos['balance']}"
                );
            }
            DB::commit();

            $this->info('Snapshots generados correctamente.');

            return Command::SUCCESS;
            
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error(
                'Error geneando snapshot financiero',
                [
                    'message' => $th->getMessage(),
                    'line' => $th->getLine(),
                    'file' => $th->getFile(),
                ]
            );
            $this->error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
