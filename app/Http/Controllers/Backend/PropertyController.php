<?php

namespace App\Http\Controllers\Backend;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\Facility;
use App\Models\MultiImage;
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
        $extension = $img->extension();
        $file_name = uniqid().'.'.$extension;

        // create new manager instance with desired driver
        $manager = new ImageManager(new Driver());
        $image = $manager->read($img);
        $image->resize(200, 150);
        $image->save('uploads/property/thambnail/'.$file_name);

        $property_id = Property::insertGetId([      //Return the ID of the inserted item
            'ptype_id' => $request->property_id,
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

        ///Multiple Image Upload From Here ///
        $images = $request->file('multi_img');
        foreach($images as $img){
            $extensions = $img->extension();
            $multi_name = uniqid().'.'.$extensions;

            // create new manager instance with desired driver
            $managers = new ImageManager(new Driver());
            $imagei = $managers->read($img);
            $imagei->resize(770, 520);
            $imagei->save('uploads/property/multi_img/'.$multi_name);

            MultiImage::insert([
                'property_id' => $property_id,
                'photo_name' => $multi_name,
                'created_at' => Carbon::now(),
            ]);
        } //End foreach
        ///End Multiple Image Upload From Here ///

        ///Facilities Added From Here ///
        $facilities = count($request->facility_name);
        if($facilities != NULL){
            for($i=0; $i < $facilities; $i++){
                $cnt = new Facility();
                $cnt->property_id = $property_id;
                $cnt->facility_name = $request->facility_name[$i];
                $cnt->distance = $request->distance[$i];
                $cnt->save();
            }
        }

        $notification = array(
            'message' => "Property Inserted Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('all.property')->with($notification);

    } //End Method
}
