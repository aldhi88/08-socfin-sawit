<?php

namespace App\Http\Controllers;

use App\Models\TcBottleInit;
use App\Models\TcBottleInitDetail;
use App\Models\TcInit;
use App\Models\TcLiquidBottle;
use App\Models\TcLiquidOb;
use App\Models\TcLiquidTransaction;
use App\Models\TcLiquidTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LiquidListController extends Controller
{
    public function index()
    {
        $data['title'] = "Liquid Per Sample";
        $data['desc'] = "Display all available data";
        $data['column1'] = TcBottleInit::where('keyword','liquid_column1')->first()->getAttribute('column_name');
        $data['column2'] = TcBottleInit::where('keyword','liquid_column2')->first()->getAttribute('column_name');
        return view('modules.liquid_list.index',compact('data'));
    }
    public function dt()
    {
        $q = TcBottleInitDetail::select('tc_bottle_id')
            ->whereHas('tc_bottle_inits',function(Builder $q){
                $q->where('keyword','liquid_column1');
            })->get()->toArray();
        $aryBottleCol1 = array_column($q, 'tc_bottle_id');
        $q = TcBottleInitDetail::select('tc_bottle_id')
            ->whereHas('tc_bottle_inits',function(Builder $q){
                $q->where('keyword','liquid_column2');
            })->get()->toArray();
        $aryBottleCol2 = array_column($q, 'tc_bottle_id');
        $data = TcInit::select(['tc_inits.*'])
            ->whereHas('tc_liquid_bottles')
            ->with([
                'tc_samples',
            ])
            ->withCount([
                'tc_liquid_bottles as first_total' => function($q){
                    $q->select(DB::raw('SUM(bottle_count)'))->where('status','!=',0);
                }
            ])
            ->withCount([
                'tc_liquid_bottles as first_total_column1' => function($q) use($aryBottleCol1){
                    $q->select(DB::raw('SUM(bottle_count)'))->whereIn('tc_bottle_id',$aryBottleCol1)
                    ->where('status','!=',0);
                }
            ])
            ->withCount([
                'tc_liquid_bottles as first_total_column2' => function($q) use($aryBottleCol2){
                    $q->select(DB::raw('SUM(bottle_count)'))->whereIn('tc_bottle_id',$aryBottleCol2)
                    ->where('status','!=',0);
                }
            ])
        ;
        // dd($data->get()->toArray());
        return DataTables::of($data)
            ->addColumn('sample_number_format',function($data){
                $el = '<p class="mb-0"><strong>'.$data->tc_samples->sample_number_display.'</strong></p>';
                $el .= '
                    <p class="mb-0">
                        <a class="text-primary" href="'.route('liquid-lists.show',$data->id).'">Detail</a>
                ';
                $el .= '</p>';
                return $el;
            })
            ->addColumn('column1',function($data){
                if (is_null($data->first_total_column1)) {
                    return 0;
                }
                $q = TcBottleInitDetail::select('tc_bottle_id')
                    ->whereHas('tc_bottle_inits',function(Builder $q){
                        $q->where('keyword','liquid_column1');
                    })->get()->toArray();
                $aryBottleId = array_column($q, 'tc_bottle_id');
                $q = TcLiquidBottle::select('id')->where('tc_init_id',$data->id)
                    ->where('status','!=',0)
                    ->whereIn('tc_bottle_id',$aryBottleId)->get();
                $usedBottle = 0;
                foreach ($q as $key => $value) {
                    $usedBottle += TcLiquidBottle::usedBottle($value->id);
                }
                return $data->first_total_column1 - $usedBottle;
            })
            ->addColumn('column2',function($data){
                if (is_null($data->first_total_column2)) {
                    return 0;
                }
                $q = TcBottleInitDetail::select('tc_bottle_id')
                    ->whereHas('tc_bottle_inits',function(Builder $q){
                        $q->where('keyword','liquid_column2');
                    })->get()->toArray();
                $aryBottleId = array_column($q, 'tc_bottle_id');

                $q = TcLiquidBottle::select('id')->where('tc_init_id',$data->id)
                    ->where('status','!=',0)
                    ->whereIn('tc_bottle_id',$aryBottleId)->get();
                $usedBottle = 0;
                foreach ($q as $key => $value) {
                    $usedBottle += TcLiquidBottle::usedBottle($value->id);
                }
                return $data->first_total_column2 - $usedBottle;
            })
            ->addColumn('total_bottle_active',function($data){
                if (is_null($data->first_total)) {
                    return 0;
                }
                $q = TcLiquidBottle::select('id')
                    ->where('tc_init_id',$data->id)
                    ->where('status','!=',0)
                    ->get();
                $usedBottle = 0;
                foreach ($q as $key => $value) {
                    $usedBottle += TcLiquidBottle::usedBottle($value->id);
                }
                return $data->first_total - $usedBottle;
            })
            ->rawColumns(['sample_number_format'])
            ->smart(false)
            ->toJson();
    }

    public function show($id)
    {
        $data['title'] = "Liquid List Data";
        $data['desc'] = "Display all liquid bottle list";
        $data['initId'] = $id;
        $q = TcInit::where('id',$id)->first();
        $data['sampleNumber'] = $q->tc_samples->sample_number_display;
        $data['column1'] = TcBottleInit::where('keyword','liquid_column1')->first()->getAttribute('column_name');
        $data['column2'] = TcBottleInit::where('keyword','liquid_column2')->first()->getAttribute('column_name');
        return view('modules.liquid_list.show',compact('data'));
    }
    public function dtShow(Request $request)
    {
        $qCode = 'DATE_FORMAT(bottle_date, "%d/%m/%Y")';
        if(config('database.default') == 'sqlsrv'){
            $qCode = 'convert(varchar,bottle_date, 103)';
        }
        $list = ['liquid_column1','liquid_column2'];
        $q = TcBottleInit::whereIn('keyword',$list)->get();
        foreach ($q as $key => $value) {
            foreach ($value->tc_bottle_init_details as $key2 => $value2) {
                $bottleList[] = $value2->tc_bottle_id;
            }
        }
        $data = TcLiquidBottle::select([
                'tc_liquid_bottles.*',
                DB::raw($qCode.' as bottle_date_format')
            ])
            ->whereIn('tc_bottle_id',$bottleList)
            ->where('tc_init_id',$request->initId)
            ->with([
                'tc_inits',
                'tc_inits.tc_samples',
                'tc_workers',
                'tc_bottles'
            ])
        ;
        if($request->filter == 1 || !isset($request->filter)){
            $data = TcLiquidBottle::select([
                    'tc_liquid_bottles.*',
                    DB::raw($qCode.' as bottle_date_format')
                ])
                ->whereIn('tc_bottle_id',$bottleList)
                ->where('tc_init_id',$request->initId)
                ->where('status',1)
                ->with([
                    'tc_inits',
                    'tc_inits.tc_samples',
                    'tc_workers',
                    'tc_bottles'
                ])
            ;
        }
        return DataTables::of($data)
            ->filterColumn('bottle_date_format', function($query, $keyword) use($qCode) {
                $sql = $qCode.'  like ?';
                $query->whereRaw($sql, ["{$keyword}"]);
            })
            ->addColumn('last_total',function($data){
                return $data->bottle_count - TcLiquidBottle::usedBottle($data->id);
            })
            ->addColumn('column1',function($data){
                $q = TcBottleInit::where('keyword','liquid_column1')->with('tc_bottle_init_details')->get();
                $dataBottle = $q[0]->tc_bottle_init_details;
                $total = 0;
                foreach ($dataBottle as $key => $value) {
                    $bottleId = $value->tc_bottle_id;
                    if($bottleId == $data->tc_bottle_id){
                        $total = $total + $data->bottle_count;
                    }
                }
                return $total;
            })
            ->addColumn('column2',function($data){
                $q = TcBottleInit::where('keyword','liquid_column2')->with('tc_bottle_init_details')->get();
                $dataBottle = $q[0]->tc_bottle_init_details;
                $total = 0;
                foreach ($dataBottle as $key => $value) {
                    $bottleId = $value->tc_bottle_id;
                    if($bottleId == $data->tc_bottle_id){
                        $total = $total + $data->bottle_count;
                    }
                }
                return $total;
            })
            ->rawColumns(['date_work_format'])
            ->smart(false)
            ->toJson();
    }
    public function dtShow2(Request $request)
    {
        $qCode = 'DATE_FORMAT(tc_liquid_bottles.bottle_date, "%d/%m/%Y")';
        if(config('database.default') == 'sqlsrv'){
            $qCode = 'convert(varchar,tc_liquid_bottles.bottle_date, 103)';
        }
        $data = TcLiquidTransaction::select([
                'tc_liquid_transactions.*',
                DB::raw($qCode.' as bottle_date_format')
            ])
            ->leftJoin('tc_liquid_bottles','tc_liquid_bottles.id','=','tc_liquid_transactions.tc_liquid_bottle_id')
            ->with([
                'tc_inits.tc_samples',
                'tc_liquid_bottles',
                'tc_liquid_bottles.tc_workers',
                'tc_workers:id,code',
            ])
            ->where('tc_liquid_transactions.tc_init_id',$request->initId)
            ->whereHas('tc_liquid_bottles',function(Builder $q){
                $q->where('status','!=',0);
            })
        ;
        return DataTables::of($data)
            ->filterColumn('bottle_date_format', function($query, $keyword) use($qCode) {
                $sql = $qCode.'  like ?';
                $query->whereRaw($sql, ["{$keyword}"]);
            })
            ->addColumn('obs_date',function($data){
                $return = $data->tc_liquid_ob_id;
                if(!is_null($return)){
                    $return = TcLiquidOb::where('id',$data->tc_liquid_ob_id)->first()->getAttribute('ob_date');
                    $return = Carbon::parse($return)->format('d/m/Y');
                }
                return $return;
            })
            ->addColumn('transfer_date',function($data){
                $transferId = $data->tc_liquid_transfer_id;
                if(!is_null($transferId)){
                    $date = TcLiquidTransfer::where('id',$transferId)->first()->getAttribute('transfer_date');
                    return Carbon::parse($date)->format('d/m/Y');
                }
                return null;
            })
            ->smart(false)
            ->rawColumns([])
            ->toJson();
    }
}
