<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Story;
use App\Models\Testimonial;
use App\Models\Item;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function add_to_home(Request $request)
    {
        if(!empty($request->id) && !empty($request->table)){
            switch ($request->table){
                case "testimonials":
                    $model = Testimonial::find($request->id);
                    $model->show_in_home = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
                    $model->update();
                break;
                case "stories":
                    $model = Story::find($request->id);
                    $model->show_in_home = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
                    $model->update();
                break;
                case "partners":
                    $model = Partner::find($request->id);
                    $model->show_in_home = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
                    $model->update();
                    break;
                case "items":
                    $model = Item::find($request->id);
                    $model->show_in_home = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
                    $model->update();
                    break;
        }
            echo json_encode(array("status" => "success"));
        }
    }
}
