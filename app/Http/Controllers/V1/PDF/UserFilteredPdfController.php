<?php

namespace Crater\Http\Controllers\V1\PDF;

use Crater\Http\Controllers\Controller;
use Crater\Models\User;
use Illuminate\Http\Request;
use Auth;

class UserFilteredPdfController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // dd($request->all());
        $users = User::applyFilters($request->all())
            ->latest()
            ->get();
            // dd($users);
            if(count($users) > 0){
                return $users[0]->getGeneratedPDFOrStreamOfFilter($users, $request->all(), 'user');
            }else{
                return;
            }
    }
}
