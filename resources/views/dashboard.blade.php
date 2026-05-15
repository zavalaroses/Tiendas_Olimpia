<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
@section('content')
    <div class="dashboard-wrapper">

        {{-- HERO --}}
        <section class="hero-section mb-4">
            <div class="hero-overlay"></div>

            <div class="row align-items-center position-relative z-2">
                <div class="col-lg-8">
                    <span class="badge dashboard-badge mb-3">
                        <i class="fa-solid fa-couch me-2"></i>
                        Sistema Integral de Gestión
                    </span>

                    <h1 class="hero-title">
                        Bienvenido de nuevo,
                        <span class="text-gradient">
                            {{ auth()->user()->name ?? 'Administrador' }}
                        </span>
                    </h1>

                    <p class="hero-subtitle">
                        Control total de inventario, ventas, apartados, proveedores
                        y finanzas de tu mueblería desde un solo lugar.
                    </p>

                    <div class="hero-info mt-4">
                        <div class="hero-info-item">
                            <i class="fa-regular fa-calendar"></i>
                            <span id="fechaActual"></span>
                        </div>

                        <div class="hero-info-item">
                            <i class="fa-solid fa-store"></i>
                            <span>
                                Sucursal:
                                <strong>{{ session('tienda_nombre', 'Principal') }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                    <div class="hero-icon-container">
                        <i class="fa-solid fa-couch"></i>
                    </div>
                </div>
            </div>
        </section>

        {{-- KPIS --}}
        <section class="mb-4">
            <div class="row g-4">

                <div class="col-md-6 col-xl-4">
                    <div class="kpi-card">
                        <div class="icon bg-success-soft">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </div>
                        <div>
                            <small>Ventas del día</small>
                            <h3 id="ventas">$0.00</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="kpi-card">
                        <div class="icon bg-primary-soft">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <small>Inventario</small>
                            <h3 id="inventario">0 artículos</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="kpi-card">
                        <div class="icon bg-warning-soft">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <div>
                            <small>Apartados activos</small>
                            <h3 id="apartados">0</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="kpi-card">
                        <div class="icon bg-danger-soft">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                        <div>
                            <small>Entregas pendientes</small>
                            <h3 id="entregas">$0.00</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="kpi-card">
                        <div class="icon bg-info-soft">
                            <i class="fa-solid fa-cash-register"></i>
                        </div>
                        <div>
                            <small>Caja</small>
                            <h3 id="caja">$0.00</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="kpi-card">
                        <div class="icon bg-purple-soft">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div>
                            <small>Cuenta bancaria</small>
                            <h3>$0.00</h3>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- MODULOS + QUICK ACTIONS --}}
        {{-- <section>
            <div class="row g-4">

                
                <div class="col-lg-8">
                    <div class="dashboard-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="mb-1">Estado general del negocio</h4>
                                <p class="text-muted mb-0">
                                    Accede rápidamente a los módulos principales
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="module-card">
                                    <i class="fa-solid fa-warehouse"></i>
                                    <h5>Inventario</h5>
                                    <p>Control de existencias y mercancía.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="module-card">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    <h5>Ventas</h5>
                                    <p>Registro y control de ventas.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="module-card">
                                    <i class="fa-solid fa-wallet"></i>
                                    <h5>Finanzas</h5>
                                    <p>Caja, banco y movimientos.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="module-card">
                                    <i class="fa-solid fa-users"></i>
                                    <h5>Proveedores</h5>
                                    <p>Compras y estado de cuenta.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div class="col-lg-4">
                    <div class="dashboard-card">
                        <h4 class="mb-4">
                            <i class="fa-solid fa-bolt me-2 text-warning"></i>
                            Acciones rápidas
                        </h4>

                        <div class="quick-actions">

                            <a href="#" class="quick-btn">
                                <i class="fa-solid fa-plus"></i>
                                Nueva venta
                            </a>

                            <a href="#" class="quick-btn">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                Nuevo apartado
                            </a>

                            <a href="#" class="quick-btn">
                                <i class="fa-solid fa-cart-plus"></i>
                                Registrar compra
                            </a>

                            <a href="#" class="quick-btn">
                                <i class="fa-solid fa-cash-register"></i>
                                Abrir caja
                            </a>

                            <a href="#" class="quick-btn">
                                <i class="fa-solid fa-user-tie"></i>
                                Estado de cuenta proveedor
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </section> --}}

        {{-- FOOTER --}}
        {{-- <section class="text-center mt-5">
            <p class="dashboard-footer-text">
                “Control inteligente para hacer crecer tu mueblería.”
            </p>
        </section> --}}

    </div>

    <script src="/js/dashboard/init.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const fecha = new Date();

            const opciones = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('fechaActual').innerText =
                fecha.toLocaleDateString('es-MX', opciones);
            dao.getDataDashboard();
        });
    </script>
@endsection
