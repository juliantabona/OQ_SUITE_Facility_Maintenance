@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">

                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body pt-3 pl-3 pr-3 pb-2">
                            <div class="align-items-center d-flex float-left justify-content-between mr-3 mt-0">
                                <div class="d-inline-block">
                                    <div class="bg-primary px-md-4 rounded">
                                        <i class="d-inline-block icon-md icon-user pb-2 pt-3 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h6 class="card-title mb-0">Statistics</h6>
                                <div class="d-inline-block pt-3">
                                    <div class="d-lg-flex">
                                        <h4 class="mb-0">65 Contractors</h4>
                                        <span class="ml-2 mr-2">|</span>
                                        <span class="mb-0 text-black-50"><a href="#">Gaborone (23)</a></span>
                                        <span class="ml-2 mr-2">|</span>
                                        <span class="mb-0 text-black-50"><a href="#">Palapye (17)</a></span>
                                        <span class="ml-2 mr-2">|</span>
                                        <span class="mb-0 text-black-50"><a href="#">Francistown (10)</a></span>
                                        <span class="ml-2 mr-2">|</span>
                                        <span class="mb-0 text-black-50"><a href="#">Lobatse (15)</a></span>
                                        <div class="d-flex align-items-center ml-lg-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            <h3 class="card-title mb-3 mt-4">Contractors / <span class="text-primary">Gaborone</span></h3>
                            <div class="d-flex table-responsive">
                                <div class="btn-group ml-auto mr-2 border-0">
                                <input type="text" class="form-control" placeholder="Search Here">
                                <button class="btn btn-sm btn-primary"><i class="icon-magnifier icons"></i> Search</button>
                                </div>
                                <div class="btn-group">
                                <button type="button" class="btn btn-light"><i class="mdi mdi-printer"></i></button>
                                <button type="button" class="btn btn-light"><i class="mdi mdi-dots-vertical"></i></button>
                                </div>
                            </div>
                            <div class="table-responsive table-hover">
                                <table class="table mt-3 border-top">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%">Name</th>
                                            <th style="width: 15%">City/Town</th>
                                            <th style="width: 15%">Tel</th>
                                            <th style="width: 15%">Email</th>
                                            <th style="width: 15%">Jobs Assigned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <tr class='clickable-row' data-href='/contractors/1'>
                                                <td>DropHill (PTY) LTD</td>
                                                <td>Gaborone</td>
                                                <td>+267 3902321</td>
                                                <td>enquiries@drophill.co.bw</td>
                                                <td>4</td>
                                            </tr>
                                            <tr class='clickable-row' data-href='/contractors/1'>
                                                <td>SandyWorks (PTY) LTD</td>
                                                <td>Gaborone</td>
                                                <td>+267 3909231</td>
                                                <td>enquiries@sandyworks.co.bw</td>
                                                <td>3</td>
                                            </tr>
                                            <tr class='clickable-row' data-href='/contractors/1'>
                                                <td>Inkdot (PTY) LTD</td>
                                                <td>Gaborone</td>
                                                <td>+267 3902098</td>
                                                <td>enquiries@inkdot.co.bw</td>
                                                <td>0</td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                <p class="mb-3 mb-sm-0">Showing 1 to 5 of 20 entries</p>
                                <nav>
                                    <ul class="pagination pagination-info mb-0">
                                        <li class="page-item">
                                            <a class="page-link">
                                                <i class="mdi mdi-chevron-left"></i>
                                            </a>
                                        </li>
                                        <li class="page-item active">
                                            <a class="bg-secondary border-dark page-link text-dark">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link">4</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link">
                                                <i class="mdi mdi-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="d-flex float-right mt-4">
                                <button class="btn btn-primary btn-sm">View All
                                    <i class="icon-arrow-right-circle icons ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <!-- Custom js for this page-->
    <script src="{{ asset('js/custom/dashboard.js') }}"></script>
    <script src="{{ asset('js/custom/chart.js') }}"></script>
    <!-- End custom js for this page-->

@endsection