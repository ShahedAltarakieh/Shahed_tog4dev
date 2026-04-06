<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExcelSheet;
use App\Models\ExcelOrders;
use App\Models\Item;
use App\Models\QuickContribution;
use App\Models\MappingZbooniItem;
use App\Exports\PaymentsCliqExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\ExtractPaymentSheetJob;
use App\Jobs\ProcessPaymentsSheetToOrderJob;

class ExcelController extends Controller
{
    public function index(){
        $data = ExcelSheet::all();
        
        return view('admin.excel.index', compact('data'));
    }

    public function create(){
        return view('admin.excel.create');
    }

    public function show($id){
        $excel_sheet = ExcelSheet::find($id);
        $data = ExcelOrders::where('excel_sheet_id', $id)->get();
        if($excel_sheet){
            return view('admin.excel.show_orders', compact('data', 'excel_sheet'));
        } else{
            return abort(404);
        }
    }

    public function download_excel($id, $type){
        $excel_sheet = ExcelSheet::find($id);
        if(!$excel_sheet){
            return abort(404);
        }
        $fileName = $excel_sheet->file_name;
        // Get the filename without extension
        $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);

        // Get the extension
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Build the new filename
        $newFileName = $nameWithoutExt . ' - '.$type.'.' . $extension;

        $data = ExcelOrders::where('excel_sheet_id', $id)->get();
        return Excel::download(new PaymentsCliqExport($id, $type), $newFileName);
    }

    public function store(Request $request){
        // Validate the request data
        $rules = [
            'excel_file' => 'required|mimes:xlsx,csv,xls|max:5048',
        ];

        $validated = $request->validate($rules);
        
        // Retrieve the uploaded file
        $file = $request->file('excel_file');

        // Get the file name
        $fileName = $file->getClientOriginalName();
        $excel = ExcelSheet::create(["file_name" => $fileName, "status" => 0]);
        $excel->addMedia($request->file('excel_file'))->toMediaCollection('excel_sheets');

        if ($excel) {
            ExtractPaymentSheetJob::dispatch($excel->id)->delay(2);
            return redirect()->route('excel.index')->with('success', __('app.add successfully'));
        }
    }

    public function runJob($id){
        $excel = ExcelSheet::find($id);
        if(!$excel){
            return abort(404);
        }
        ProcessPaymentsSheetToOrderJob::dispatch($excel->id)->delay(2);
        return redirect()->route('excel.index')->with('success', "Running");
    }

    public function mappingData(Request $request){
        $allItems = Item::all();
        $quickItems = QuickContribution::all(); // For كويك types
        $items = MappingZbooniItem::with('item', 'quickContribution')->orderBy('id', 'desc')->get();
        return view('admin.excel.show_mapping', compact('items', 'allItems', 'quickItems'));
    }

    public function storeMappingData(Request $request)
    {
        // Validate the form
        $request->validate([
            'id.*' => 'nullable|integer|exists:mapping_zbooni_item,id',
            'item_id.*' => 'required|integer',
            'zbooni_name.*' => 'required|string|max:255',
            'model_type.*' => 'required|string|max:255',
        ]);

        // Collect all submitted IDs
        $submittedIds = $request->id ?? [];

        // Delete any existing records NOT in submitted IDs
        MappingZbooniItem::whereNotIn('id', $submittedIds)->delete();

        // Loop through submitted rows to update or create
        foreach($request->item_id as $index => $item_id) {
            $id = $submittedIds[$index] ?? null;
            $zbooni_name = $request->zbooni_name[$index];
            $model_type = $request->model_type[$index];

            if($id) {
                // Update existing record
                $mapping = MappingZbooniItem::find($id);
                if($mapping) {
                    $mapping->update([
                        'item_id' => $item_id,
                        'zbooni_name' => $zbooni_name,
                        'model_type' => $model_type,
                    ]);
                }
            } else {
                // Create new record
                MappingZbooniItem::create([
                    'item_id' => $item_id,
                    'zbooni_name' => $zbooni_name,
                    'model_type' => $model_type,
                ]);
            }
        }

        return redirect()->back()->with('success', __('app.updated successfully'));
    }

    public function updateMap(Request $request)
    {
        $data = $request->validate([
            'model_type' => 'required|string',
            'item_id' => 'required|integer',
            'zbooni_name' => 'required|string',
        ]);

        if ($request->id) {
            $map = MappingZbooniItem::findOrFail($request->id);
            $map->update($data);
        } else {
            $map = MappingZbooniItem::create($data);
        }

        return response()->json(['id' => $map->id]);
    }

    public function deleteMap(Request $request)
    {
        $map = MappingZbooniItem::findOrFail($request->id);
        $map->delete();
        return response()->json(['success' => true]);
    }

}
