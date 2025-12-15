<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Cuenta</title>
    <style>
        :root{--bg:#f6f7f9;--card:#fff;--muted:#6b7280;--accent:#10b981;--danger:#ef4444;--glass:rgba(0,0,0,0.04)}
        html,body{height:100%;margin:0;font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, 'Helvetica Neue', Arial;color:#111827;background:var(--bg)}
        .container{max-width:1100px;margin:28px auto;padding:20px}


        /* Header */
        .header{display:flex;gap:16px;align-items:center;justify-content:space-between}
        .store{display:flex;gap:12px;align-items:center}
        .logo{width:64px;height:64px;border-radius:8px;background:linear-gradient(135deg,#06b6d4,#7c3aed);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}
        .store h1{margin:0;font-size:18px}
        .store p{margin:0;color:var(--muted);font-size:13px}


        /* Controls */
        .controls{display:flex;gap:8px;align-items:center}
        .controls input[type="date"], .controls select{padding:8px 10px;border-radius:6px;border:1px solid #e5e7eb;background:#fff}
        .btn{background:var(--accent);color:#fff;padding:8px 12px;border-radius:8px;border:none;cursor:pointer}
        .btn.ghost{background:transparent;border:1px solid #e5e7eb;color:var(--muted)}
        .btn.danger{background:var(--danger)}


        /* Quick summary cards */
        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:18px}
        .card{background:var(--card);padding:14px;border-radius:10px;box-shadow:0 1px 2px var(--glass)}
        .card .label{font-size:12px;color:var(--muted)}
        .card .value{font-size:20px;font-weight:700;margin-top:6px}
        .card.small{padding:10px}


        /* Main area */
        .main{display:flex;gap:16px;margin-top:18px}
        .left{flex:1}
        .right{width:360px}


        /* Transactions table */
        .table-wrap{background:var(--card);border-radius:10px;padding:12px;box-shadow:0 1px 2px var(--glass)}
        table{width:100%;border-collapse:collapse;font-size:13px}
        thead th{font-weight:700;text-align:left;padding:10px 8px;border-bottom:1px solid #eef2f7;color:var(--muted)}
        tbody td{padding:10px 8px;border-bottom:1px dashed #f1f5f9}
        .muted{color:var(--muted);font-size:12px}


        /* Right summary */
        .summary{background:var(--card);padding:14px;border-radius:10px;box-shadow:0 1px 2px var(--glass)}
        .summary h3{margin:0 0 10px 0}
        .summary-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #f1f5f9}
        .summary-row.total{font-weight:800;font-size:18px}


        /* print-friendly */
        @media print{
        body{background:#fff}
        .controls,.header .controls,.btn{display:none}
        .container{max-width:100%;margin:0;padding:0}
        .grid{page-break-inside:avoid}
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="row">
                <div class="col-md-10">
                    @if(Auth::user()->rol == 1)
                    <div class="col-md-4">
                        <select name="tiendas" id="tiendas" class="form-control"></select> 
                    </div>
                @endif
                </div>
                <div class="col-md-2">
                    <button id="btnNewIngreso" type="button" name="btnNewIngreso" class="btnNuevoUsuario">Ingreso</button>
                    {{-- <button id="btnNewEgreso" type="button" name="btnNewEgreso" class="btnNuevoUsuario">Egreso</button> --}}
                </div>
            </div>
        <div class="main">
            <div class="left">
                <div class="table-wrap">
                    <table id="tbl_transacciones_cuenta" class="table table-borderless table-centered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tienda</th>
                                <th>Tipo</th>
                                <th>Concepto</th>
                                <th>Monto</th>
                                <th>Detalle</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody id="movimientos">
                        </tbody>
                    </table>
                </div>
            </div>


            <aside class="right">
                <div class="summary">
                    <h3>Resumen de cuenta</h3>
                    
                    <div class="summary-row"><span>Ingresos</span><span id="ingresos">$ 21,530.00</span></div>
                    <div class="summary-row"><span>Egresos</span><span id="salidas">$ 1,400.00</span></div>
                    <div class="summary-row total"><span>Total general</span><span id="saldoCuenta">$ 19,130.00</span></div>


                    {{-- <div style="margin-top:12px;display:flex;gap:8px">
                        <button class="btn" id="btnCerrarCorte">Cerrar corte</button>
                    </div> --}}
                </div>
            </aside>
        </div>
    </div>
</body>
@include('caja.modalAddIngresoCuenta')
<script src="/js/utilerias.js"></script>
<script src="/js/cuenta/init.js"></script>
<script>
    $(document).ready(function () {
        dao.getCatTiendas('tiendas','');
        dao.getDataCuenta('');
    })
</script>

@endsection
