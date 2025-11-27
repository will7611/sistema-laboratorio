@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Sistema - Laboratorio</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                            <li class="breadcrumb-item active">App</li>
                                    </ol>
            </div>
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col">

        <div class="h-100">
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            @php
                                $hour = now()->format('H'); // Hora en formato 24h
                                if ($hour >= 5 && $hour < 12) {
                                    $greeting = 'Buen día';
                                } elseif ($hour >= 12 && $hour < 18) {
                                    $greeting = 'Buenas tardes';
                                } else {
                                    $greeting = 'Buenas noches';
                                }
                            @endphp
                               
                            <h4 class="fs-16 mb-1">{{$greeting}}, {{ auth()->user()->name}}!</h4>
                            {{-- <p class="text-muted mb-0">Here's what's happening with your store
                                today.</p> --}}
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-sm-auto">
                                        <div class="input-group">
                                            <input type="text"
                                                id="fecha"
                                                class="form-control border-0 dash-filter-picker shadow"
                                                data-provider="flatpickr" 
                                                data-range-date="true"
                                                data-date-format="d M, Y"
                                                data-deafult-date="{{ date('d M, Y') }} to {{ date('d M, Y') }}"
                                                data-locale="es"
                                                />
                                            <div
                                                class="input-group-text bg-primary border-primary text-white">
                                                <i class="ri-calendar-2-line"></i></div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-soft-secondary"><i
                                                class="ri-add-circle-line align-middle me-1"></i>
                                            Agregar Articulo</button>
                                    </div>
                                    <!--end col-->
                                    <div class="col-auto">
                                        <button type="button"
                                            class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn"><i
                                                class="ri-pulse-line"></i></button>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                        Total Usuarios</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        {{$totalUsers}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                            class="counter-value" data-target="{{$totalUsers}}">0</span>
                                    </h4>
                                    <a href="{{route('users.index')}}" class="text-decoration-underline text-white-50">Ver Usuarios</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-user-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                        Categorias</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                        <i class="ri-arrow-right-down-line fs-13 align-middle"></i>
                                       
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                            class="counter-value" data-target="">0</span></h4>
                                    <a href="" class="text-decoration-underline text-white-50">Ver Categorias</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-shopping-bag text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                Productos</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                                                       </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4"><span
                                            class="counter-value" data-target="   ">0  </span>
                                    </h4>
                                    <a href="" class="text-decoration-underline text-white-50">Ver Productos</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-user-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate bg-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-bold text-white-50 text-truncate mb-0">
                                        Pacientes</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-white fs-14 mb-0">
                                       {{$totalPaciente}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">$<span
                                            class="counter-value" data-target="{{$totalPaciente}}">0</span>
                                    </h4>
                                    <a href="{{route('pacientes.index')}}"
                                        class="text-decoration-underline text-white-50">Ver pacientes</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-light rounded fs-3">
                                        <i class="bx bx-wallet text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div> <!-- end row-->

            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Estadisticas</h4>
                            <div>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    ALL
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    1M
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    6M
                                </button>
                                <button type="button" class="btn btn-soft-primary btn-sm">
                                    1Y
                                </button>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-soft-light">
                            <div class="row g-0 text-center">
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="{{$totalUsers}}">0</span></h5>
                                        <p class="text-muted mb-0">Usuarios</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="">0</span></h5>
                                        <p class="text-muted mb-0">Categorias</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="">0</span></h5>
                                        <p class="text-muted mb-0">Productos</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div
                                        class="p-3 border border-dashed border-start-0 border-end-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="{{$totalPaciente}}">0</span></h5>
                                        <p class="text-muted mb-0">pacientes</p>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body p-0 pb-2">
                            <div class="w-100">
                                <div id="customer_impression_charts"
                                    data-colors='["--vz-warning", "--vz-primary", "--vz-danger"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

            <div class="col-xl-4">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Store Visits by Source</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="text-muted">Report<i
                                                class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Download Report</a>
                                        <a class="dropdown-item" href="#">Export</a>
                                        <a class="dropdown-item" href="#">Import</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div id="store-visits-source"
                                data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]'
                                class="apex-charts" dir="ltr"></div>
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->



                <!-- end col -->
            </div>

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Prestamos</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="fw-bold text-uppercase fs-12">Sort by:
                                        </span><span class="text-muted">Today<i
                                                class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Today</a>
                                        <a class="dropdown-item" href="#">Yesterday</a>
                                        <a class="dropdown-item" href="#">Last 7 Days</a>
                                        <a class="dropdown-item" href="#">Last 30 Days</a>
                                        <a class="dropdown-item" href="#">This Month</a>
                                        <a class="dropdown-item" href="#">Last Month</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table
                                    class="table table-hover table-centered align-middle table-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar-sm bg-light rounded p-1 me-2">
                                                        <img src="http://localhost/creative2/public/assets/images/products/img-1.png"
                                                            alt="" class="img-fluid d-block" />
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-14 my-1"><a
                                                                href="http://localhost/creative2/public/apps-ecommerce-product-details"
                                                                class="text-reset">Branded
                                                                T-Shirts</a></h5>
                                                        <span class="text-muted">24 Apr 2021</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">$29.00</h5>
                                                <span class="text-muted">Price</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">62</h5>
                                                <span class="text-muted">Orders</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">510</h5>
                                                <span class="text-muted">Stock</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 my-1 fw-normal">$1,798</h5>
                                                <span class="text-muted">Amount</span>
                                            </td>
                                        </tr>
                                     
                                    </tbody>
                                </table>
                            </div>

                            <div
                                class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                <div class="flex-shrink-0">
                                    <div class="text-muted">Showing <span
                                            class="fw-semibold">5</span> of <span
                                            class="fw-semibold">25</span> Results
                                    </div>
                                </div>
                                <ul class="pagination pagination-separated pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link">←</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">→</a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Articulos</h4>
                            <div class="flex-shrink-0">
                                <div class="dropdown card-header-dropdown">
                                    <a class="text-reset dropdown-btn" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="text-muted">Report<i
                                                class="mdi mdi-chevron-down ms-1"></i></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Download Report</a>
                                        <a class="dropdown-item" href="#">Export</a>
                                        <a class="dropdown-item" href="#">Import</a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table
                                    class="table table-centered table-hover align-middle table-nowrap mb-0">
                                    <tbody>
                                        {{-- @forelse ($products as $product) --}}
                                            
                                        
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-2">
                                                        <img src="http://localhost/creative2/public/assets/images/companies/img-1.png"
                                                            alt="" class="avatar-sm p-2" />
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-14 my-1 fw-medium"><a
                                                                href="http://localhost/creative2/public/apps-ecommerce-seller-details"
                                                                class="text-reset"></a>
                                                        </h5>
                                                        <span class="text-muted"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">Bags and Wallets</span>
                                            </td>
                                            <td>
                                                <p class="mb-0">8547</p>
                                                <span class="text-muted">Stock</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">$541200</span>
                                            </td>
                                            <td>
                                                <h5 class="fs-14 mb-0">32%<i
                                                        class="ri-bar-chart-fill text-success fs-16 align-middle ms-2"></i>
                                                </h5>
                                            </td>
                                       {{-- @empty
                                            <p>No hay datos</p>
                                        @endforelse --}}
                                    </tbody>
                                </table><!-- end table -->
                            </div>

                            <div
                                class="align-items-center mt-4 pt-2 justify-content-between d-flex">
                                <div class="flex-shrink-0">
                                    <div class="text-muted">Showing <span
                                            class="fw-semibold">5</span> of <span
                                            class="fw-semibold">25</span> Results
                                    </div>
                                </div>
                                <ul class="pagination pagination-separated pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a href="#" class="page-link">←</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">1</a>
                                    </li>
                                    <li class="page-item active">
                                        <a href="#" class="page-link">2</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">3</a>
                                    </li>
                                    <li class="page-item">
                                        <a href="#" class="page-link">→</a>
                                    </li>
                                </ul>
                            </div>

                        </div> <!-- .card-body-->
                    </div> <!-- .card-->
                </div> <!-- .col-->
            </div> <!-- end row-->

            <div class="row">
               

           
            </div> <!-- end row-->

        </div> <!-- end .h-100-->

    </div> <!-- end col -->

    <div class="col-auto layout-rightside-col">
        <div class="overlay"></div>
        <div class="layout-rightside">
            <div class="card h-100 rounded-0">
                <div class="card-body p-0">
                    <div class="p-3">
                        <h6 class="text-muted mb-0 text-uppercase fw-bold fs-13">Recent Activity
                        </h6>
                    </div>
                    <div data-simplebar style="max-height: 410px;" class="p-3 pt-0">
                        <div class="acitivity-timeline acitivity-main">
                            <div class="acitivity-item d-flex">
                                <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                    <div
                                        class="avatar-title bg-soft-success text-success rounded-circle">
                                        <i class="ri-shopping-cart-2-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Purchase by James Price</h6>
                                    <p class="text-muted mb-1">Product noise evolve smartwatch </p>
                                    <small class="mb-0 text-muted">02:14 PM Today</small>
                                </div>
                            </div>
                            <div class="acitivity-item py-3 d-flex">
                                <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                                    <div
                                        class="avatar-title bg-soft-danger text-danger rounded-circle">
                                        <i class="ri-stack-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Added new <span
                                            class="fw-semibold">style collection</span></h6>
                                    <p class="text-muted mb-1">By Nesta Technologies</p>
                                    <div class="d-inline-flex gap-2 border border-dashed p-2 mb-2">
                                        <a href="http://localhost/creative2/public/apps-ecommerce-product-details"
                                            class="bg-light rounded p-1">
                                            <img src="http://localhost/creative2/public/assets/images/products/img-8.png" alt=""
                                                class="img-fluid d-block" />
                                        </a>
                                        <a href="http://localhost/creative2/public/apps-ecommerce-product-details"
                                            class="bg-light rounded p-1">
                                            <img src="http://localhost/creative2/public/assets/images/products/img-2.png" alt=""
                                                class="img-fluid d-block" />
                                        </a>
                                        <a href="http://localhost/creative2/public/apps-ecommerce-product-details"
                                            class="bg-light rounded p-1">
                                            <img src="http://localhost/creative2/public/assets/images/products/img-10.png" alt=""
                                                class="img-fluid d-block" />
                                        </a>
                                    </div>
                                    <p class="mb-0 text-muted"><small>9:47 PM Yesterday</small></p>
                                </div>
                            </div>
                            <div class="acitivity-item py-3 d-flex">
                                <div class="flex-shrink-0">
                                    <img src="http://localhost/creative2/public/assets/images/users/avatar-2.jpg" alt=""
                                        class="avatar-xs rounded-circle acitivity-avatar">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Natasha Carey have liked the products
                                    </h6>
                                    <p class="text-muted mb-1">Allow users to like products in your
                                        WooCommerce store.</p>
                                    <small class="mb-0 text-muted">25 Dec, 2021</small>
                                </div>
                            </div>
                            <div class="acitivity-item py-3 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs acitivity-avatar">
                                        <div class="avatar-title rounded-circle bg-secondary">
                                            <i class="mdi mdi-sale fs-14"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Today offers by <a
                                            href="http://localhost/creative2/public/apps-ecommerce-seller-details"
                                            class="link-secondary">Digitech Galaxy</a></h6>
                                    <p class="text-muted mb-2">Offer is valid on orders of Rs.500 Or
                                        above for selected products only.</p>
                                    <small class="mb-0 text-muted">12 Dec, 2021</small>
                                </div>
                            </div>
                            <div class="acitivity-item py-3 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs acitivity-avatar">
                                        <div
                                            class="avatar-title rounded-circle bg-soft-danger text-danger">
                                            <i class="ri-bookmark-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Favoried Product</h6>
                                    <p class="text-muted mb-2">Esther James have favorited product.
                                    </p>
                                    <small class="mb-0 text-muted">25 Nov, 2021</small>
                                </div>
                            </div>
                            <div class="acitivity-item py-3 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs acitivity-avatar">
                                        <div class="avatar-title rounded-circle bg-secondary">
                                            <i class="mdi mdi-sale fs-14"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Flash sale starting <span
                                            class="text-primary">Tomorrow.</span></h6>
                                    <p class="text-muted mb-0">Flash sale by <a
                                            href="javascript:void(0);"
                                            class="link-secondary fw-semibold">Zoetic Fashion</a>
                                    </p>
                                    <small class="mb-0 text-muted">22 Oct, 2021</small>
                                </div>
                            </div>
                            <div class="acitivity-item py-3 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs acitivity-avatar">
                                        <div
                                            class="avatar-title rounded-circle bg-soft-info text-info">
                                            <i class="ri-line-chart-line"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Monthly sales report</h6>
                                    <p class="text-muted mb-2"><span class="text-danger">2 days
                                            left</span> notification to submit the monthly sales
                                        report. <a href="javascript:void(0);"
                                            class="link-warning text-decoration-underline">Reports
                                            Builder</a></p>
                                    <small class="mb-0 text-muted">15 Oct</small>
                                </div>
                            </div>
                            <div class="acitivity-item d-flex">
                                <div class="flex-shrink-0">
                                    <img src="http://localhost/creative2/public/assets/images/users/avatar-3.jpg" alt=""
                                        class="avatar-xs rounded-circle acitivity-avatar" />
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 lh-base">Frank Hook Commented</h6>
                                    <p class="text-muted mb-2 fst-italic">" A product that has
                                        reviews is more likable to be sold than a product. "</p>
                                    <small class="mb-0 text-muted">26 Aug, 2021</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mt-2">
                        <h6 class="text-muted mb-3 text-uppercase fw-bold fs-13">Top 10 Categories
                        </h6>

                        <ol class="ps-3 text-muted">
                            <li class="py-1">
                                <a href="#" class="text-muted">Mobile & Accessories <span
                                        class="float-end">(10,294)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Desktop <span
                                        class="float-end">(6,256)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Electronics <span
                                        class="float-end">(3,479)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Home & Furniture <span
                                        class="float-end">(2,275)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Grocery <span
                                        class="float-end">(1,950)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Fashion <span
                                        class="float-end">(1,582)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Appliances <span
                                        class="float-end">(1,037)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Beauty, Toys & More <span
                                        class="float-end">(924)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Food & Drinks <span
                                        class="float-end">(701)</span></a>
                            </li>
                            <li class="py-1">
                                <a href="#" class="text-muted">Toys & Games <span
                                        class="float-end">(239)</span></a>
                            </li>
                        </ol>
                        <div class="mt-3 text-center">
                            <a href="javascript:void(0);"
                                class="text-muted text-decoration-underline">View all Categories</a>
                        </div>
                    </div>
                    <div class="p-3">
                        <h6 class="text-muted mb-3 text-uppercase fw-bold fs-13">Products Reviews
                        </h6>
                        <!-- Swiper -->
                        <div class="swiper vertical-swiper" style="height: 250px;">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="card border border-dashed shadow-none">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="http://localhost/creative2/public/assets/images/companies/img-1.png"
                                                            alt="" height="30">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div>
                                                        <p
                                                            class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                            " Great product and looks great, lots of
                                                            features. "</p>
                                                        <div
                                                            class="fs-11 align-middle text-warning">
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mb-0 text-muted">
                                                        - by <cite title="Source Title">Force
                                                            Medicines</cite>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card border border-dashed shadow-none">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="http://localhost/creative2/public/assets/images/users/avatar-3.jpg"
                                                        alt="" class="avatar-sm rounded">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div>
                                                        <p
                                                            class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                            " Amazing template, very easy to
                                                            understand and manipulate. "</p>
                                                        <div
                                                            class="fs-11 align-middle text-warning">
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-half-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mb-0 text-muted">
                                                        - by <cite title="Source Title">Henry
                                                            Baird</cite>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card border border-dashed shadow-none">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 avatar-sm">
                                                    <div class="avatar-title bg-light rounded">
                                                        <img src="http://localhost/creative2/public/assets/images/companies/img-8.png"
                                                            alt="" height="30">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div>
                                                        <p
                                                            class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                            "Very beautiful product and Very helpful
                                                            customer service."</p>
                                                        <div
                                                            class="fs-11 align-middle text-warning">
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-line"></i>
                                                            <i class="ri-star-line"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mb-0 text-muted">
                                                        - by <cite title="Source Title">Zoetic
                                                            Fashion</cite>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="card border border-dashed shadow-none">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src="http://localhost/creative2/public/assets/images/users/avatar-2.jpg"
                                                        alt="" class="avatar-sm rounded">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div>
                                                        <p
                                                            class="text-muted mb-1 fst-italic text-truncate-two-lines">
                                                            " The product is very beautiful. I like
                                                            it. "</p>
                                                        <div
                                                            class="fs-11 align-middle text-warning">
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-fill"></i>
                                                            <i class="ri-star-half-fill"></i>
                                                            <i class="ri-star-line"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-end mb-0 text-muted">
                                                        - by <cite title="Source Title">Nancy
                                                            Martino</cite>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-3">
                        <h6 class="text-muted mb-3 text-uppercase fw-bold fs-13">Customer Reviews
                        </h6>
                        <div class="bg-light px-3 py-2 rounded-2 mb-2">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="fs-16 align-middle text-warning">
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-fill"></i>
                                        <i class="ri-star-half-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <h6 class="mb-0">4.5 out of 5</h6>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-muted">Total <span class="fw-semibold">5.50k</span>
                                reviews</div>
                        </div>

                        <div class="mt-3">
                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0">5 star</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-1">
                                        <div class="progress animated-progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: 50.16%" aria-valuenow="50.16"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0 text-muted">2758</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0">4 star</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-1">
                                        <div class="progress animated-progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: 29.32%" aria-valuenow="29.32"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0 text-muted">1063</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0">3 star</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-1">
                                        <div class="progress animated-progress progress-sm">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                style="width: 18.12%" aria-valuenow="18.12"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0 text-muted">997</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0">2 star</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-1">
                                        <div class="progress animated-progress progress-sm">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: 4.98%" aria-valuenow="4.98"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0 text-muted">227</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row align-items-center g-2">
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0">1 star</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-1">
                                        <div class="progress animated-progress progress-sm">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: 7.42%" aria-valuenow="7.42"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="p-1">
                                        <h6 class="mb-0 text-muted">408</h6>
                                    </div>
                                </div>
                            </div><!-- end row -->
                        </div>
                    </div>

                    <div class="card sidebar-alert bg-light border-0 text-center mx-4 mb-0 mt-3">
                        <div class="card-body">
                            <img src="http://localhost/creative2/public/assets/images/giftbox.png" alt="">
                            <div class="mt-4">
                                <h5>Invite New Seller</h5>
                                <p class="text-muted lh-base">Refer a new seller to us and earn $100
                                    per refer.</p>
                                <button type="button"
                                    class="btn btn-primary btn-label rounded-pill"><i
                                        class="ri-mail-fill label-icon align-middle rounded-pill fs-16 me-2"></i>
                                    Invite Now</button>
                            </div>
                        </div>
                    </div>

                </div>


            </div> <!-- end card-->
        </div> <!-- end .rightbar-->

    </div> <!-- end col -->
</div>


<script>
    flatpickr("#fecha", {
        mode: "range",
        dateFormat: "d M, Y",
        locale: "es", // idioma español
        defaultDate: [new Date(), new Date()] // hoy hasta hoy
    });


    document.querySelectorAll('.counter-value').forEach(counter => {
    const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const increment = target / 200;

        if(count < target){
            counter.innerText = Math.ceil(count + increment);
            setTimeout(updateCount, 10);
        } else {
            counter.innerText = target;
        }
    };
    updateCount();
});
</script>

@endsection





{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
