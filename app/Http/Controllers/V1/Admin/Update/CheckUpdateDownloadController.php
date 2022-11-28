<?php

namespace Crater\Http\Controllers\V1\Admin\Update;

use Crater\Http\Controllers\Controller;
use Crater\Models\Setting;
use Crater\Space\Updater;
use Illuminate\Http\Request;

class CheckUpdateDownloadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        // if ((! $request->user()) || (! $request->user()->isOwner())) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You are not allowed to update this app.'
        //     ], 401);
        // }

       // set_time_limit(600); // 10 minutes

        $json = Updater::checkForUpdate(Setting::getSetting('version'));
//dd($json);
        if ($json->success && $json->version) {
            $new_version = $json->version->version;
           $zip_file_path = Updater::download($new_version);
           $temp_extract_dir = Updater::unzip($zip_file_path);
            Updater::copyFiles($temp_extract_dir);
            //Updater::deleteFiles($json);
            Updater::migrateUpdate();
            Updater::finishUpdate(Setting::getSetting('version'), $new_version);
            return response()->json([
                'success'=> true,
                'message' => 'app updated success fully'
            ]);
        }

        return response()->json($json);
    }
}
