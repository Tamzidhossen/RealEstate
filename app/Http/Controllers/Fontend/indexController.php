<?php

namespace App\Http\Controllers\Fontend;

use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Property;
use Illuminate\Http\Request;

class indexController extends Controller
{
    public function ProopertyDetails($id, $slug){
        $property = Property::findOrFail($id);
        $multiImg = MultiImage::where('property_id', $id)->get();
        $facility = Facility::where('property_id', $id)->get();

        $ameId = $property->amenities_id;
        $amenities = explode(',', $ameId);

        $similarProperty = Property::where('id', '!=', $id)->limit(3)->orderBy('id', 'DESC')->get();
        // return $similarProperty;
        return view('frontend.property.property_details', compact('property', 'multiImg', 'amenities', 'facility', 'similarProperty'));
    }
}
