<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function index()
    {
        return response()->json('404 nerasta')->setStatusCode(Response::HTTP_NOT_FOUND);
    }
}
