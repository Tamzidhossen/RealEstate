<?php

namespace App\Http\Controllers\Fontend;

use App\Http\Controllers\Controller;
use App\Models\MultiImage;
use App\Models\Property;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function ProopertyDetails($id, $slug){
        $property = Property::findOrFail($id);
        $multiImg = MultiImage::where('property_id', $id)->get();
        return view('frontend.property.property_details', compact('property', 'multiImg'));
    }
}
