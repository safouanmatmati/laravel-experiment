<?php

/*
 * This file is part of the Laravel Shop package.
 *
 * (c) Safouan MATMATI <safouan.matmati@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Base admin controller
 */
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');

        // Shares variable to defined whenever we are in the backoffice
        view()->share('is_backoffice', true);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Redirect if user is not an admin
        if (true != $request->user()->is_admin) {
            abort(403);
        }

        return view('admin/home');
    }
}
