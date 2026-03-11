<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif 
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
        th,td{
            border: 1px solid #ddd;
            padding: 8px;
        }
        th{
            background: #f2f2f2;
        }
        .title{
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="title">
        Reporte Financiero
    </div>
    <p>Tienda:
        {{$tienda}}
    </p>
    <p>
        Periodo:
        {{$inicio ?? 'Inicio'}} - {{$fin ?? 'Actual'}}
    </p>
    <table>
        <tr>
            <th>Concepto</th>
            <th>Monto</th>
        </tr>
        <tr>
            <td>Ventas</td>
            <td>${{ number_format($ventas,2) }}</td>
        </tr>
        <tr>
            <td>Gastos</td>
            <td>${{ number_format($gastos,2) }}</td>
        </tr>
        <tr>
            <td>Inventario</td>
            <td>${{ number_format($inventario,2) }}</td>
        </tr>
        <tr>
            <td>Dinero en caja</td>
            <td>${{ number_format($caja,2) }}</td>
        </tr>
        <tr>
            <td>Dinero en cuenta</td>
            <td>${{ number_format($cuenta,2) }}</td>
        </tr>
        <tr>
            <td>Adeudo proveedores</td>
            <td>${{ number_format($adeudo,2) }}</td>
        </tr>
        <tr>
            <td>Saldo a favor proveedores</td>
            <td>${{ number_format($saldoFavor) }}</td>
        </tr>
        <tr>
            <td>Balance</td>
            <td>${{ number_format($balance,2) }}</td>
        </tr>
    </table>
</body>
</html>