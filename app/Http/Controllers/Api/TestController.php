<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $result = \App\Company::find(1)->staff; //clients;

        return oq_api_notify($result, 200);
    }

    public function show($test_id)
    {
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request, $test_id)
    {
    }

    public function delete($test_id)
    {
    }
}
