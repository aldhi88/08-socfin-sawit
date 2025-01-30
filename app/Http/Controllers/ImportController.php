<?php

namespace App\Http\Controllers;

use App\Imports\CallusImport;
use App\Imports\EmbryoImport;
use App\Imports\GerminImport;
use App\Imports\InitsImport;
use App\Imports\LiquidImport;
use App\Imports\MaturImport;
use App\Imports\SampleForImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // public function indexSample()
    // {
    //     return view('modules.import.sample');
    // }
    public function sampleExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_sample.xlsx'));
    }
    public function sampleImport(Request $request)
    {
        Excel::import(new SampleForImport, $request->file);

        if(SampleForImport::$error != false){
            return response()->json([
                'status' => 'error',
                'data' => [
                    'type' => 'danger',
                    'icon' => 'times',
                    'el' => 'alert-area',
                    'msg' => 'Import Error, '.SampleForImport::$error,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }
    // ====================

    public function initsExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_init.xlsx'));
    }
    public function initsImport(Request $request)
    {
        Excel::import(new InitsImport, $request->file);
        if(InitsImport::$error != false){
            return response()->json([
                'status' => 'error',
                'data' => [
                    'type' => 'danger',
                    'icon' => 'times',
                    'el' => 'alert-area',
                    'msg' => 'Import Error, '.InitsImport::$error,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }

    // ==================== CALLUS

    public function callusExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_callus.xlsx'));
    }
    public function callusImport(Request $request)
    {
        Excel::import(new CallusImport, $request->file);
        if(CallusImport::$error != false){
            return response()->json([
                'status' => 'error',
                'data' => [
                    'type' => 'danger',
                    'icon' => 'times',
                    'el' => 'alert-area',
                    'msg' => 'Import Error, '.CallusImport::$error,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }

    // ==================== Embryo

    public function embryoExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_embryo.xlsx'));
    }
    public function embryoImport(Request $request)
    {
        Excel::import(new EmbryoImport, $request->file);
        if(EmbryoImport::$error != false){
            return response()->json([
                'status' => 'error',
                'data' => [
                    'type' => 'danger',
                    'icon' => 'times',
                    'el' => 'alert-area',
                    'msg' => 'Import Error, '.EmbryoImport::$error,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }

    // ==================== Liquid

    public function liquidExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_liquid.xlsx'));
    }
    public function liquidImport(Request $request)
    {
        Excel::import(new LiquidImport, $request->file);
        if(LiquidImport::$error != false){
            return response()->json([
                'status' => 'error',
                'data' => [
                    'type' => 'danger',
                    'icon' => 'times',
                    'el' => 'alert-area',
                    'msg' => 'Import Error, '.LiquidImport::$error,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }

    // ==================== Maturation

    public function maturExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_matur.xlsx'));
    }
    public function maturImport(Request $request)
    {
        Excel::import(new MaturImport, $request->file);
        if(MaturImport::$error != false){
            return response()->json([
                'status' => 'error',
                'data' => [
                    'type' => 'danger',
                    'icon' => 'times',
                    'el' => 'alert-area',
                    'msg' => 'Import Error, '.MaturImport::$error,
                ],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }

    // ==================== Germin

    public function germinExport()
    {
        return response()->download(storage_path('/app/public/form_import/form_import_germin.xlsx'));
    }
    public function germinImport(Request $request)
    {
        Excel::import(new GerminImport, $request->file);
        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => 'success',
                'icon' => 'check',
                'el' => 'alert-area',
                'msg' => 'Success, new data has been imported.',
            ],
        ]);
    }
}
