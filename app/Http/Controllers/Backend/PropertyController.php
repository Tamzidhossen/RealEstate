<?php

namespace App\Http\Controllers\Backend;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function AllProperty(){
        $property = Property::latest()->get();
        return view('backend.property.all_property', compact('property'));
    } //End Method

    public function AddProperty(){

        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        $activeAgent = User::where('status', 'active')->where('role', 'agent')->latest()->get();
        return view('backend.property.add_property', compact('propertyType', 'amenities', 'activeAgent'));
    }

    public function StoreProperty(Request $request) {
        $data = $request->amenities_id;
        $amenities = implode(',', $data);
        $code = str_pad(random_int(0, 99999), 5, '1', STR_PAD_LEFT);     //Generate 5 digit random number
        // dd($amenities);      //Check amenities array value

        $img = $request->file('property_thambnail');
        $extension = $img->exectension();
        $file_name = uniqid().'.'.$extension;

        // create new manager instance with desired driver
        $manager = new ImageManager(new Driver());
        $image = $manager->read($img);
        $image->resize(200, 150);
        $image->save('upload/property/thambnail/'.$file_name);

        $property_id = Property::insertGetId([
            'ptype_id' => $request->ptype_id,
            'amenities_id' => $amenities,
            'property_name' => $request->property_name,
            'property_slug' => Str::lower(str_replace(' ', '-', $request->property_name)).'-'.random_int(10000, 99999),
            'property_code' => $code,
            'property_status' => $request->property_status,

            'lowest_price' => $request->lowest_price,
            'max_price' => $request->max_price,
            'property_thambnail' => $file_name,
            'short_desp' => $request->short_desp,
            'long_desp' => $request->long_desp,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'garage' => $request->garage,

            'garage_size' => $request->garage_size,
            'property_size' => $request->property_size,
            'property_video' => $request->property_video,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,

            'neighborhood' => $request->neighborhood,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'featured' => $request->featured,
            'hot' => $request->hot,
            'agent_id' => $request->agent_id,
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);
        
    }
}
