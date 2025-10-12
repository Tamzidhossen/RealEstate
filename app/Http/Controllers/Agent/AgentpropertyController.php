<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Amenities;
use App\Models\Facility;
use App\Models\MultiImage;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class AgentpropertyController extends Controller
{
    public function AgentAllProperty(){

        $id = Auth::user()->id;
        $property = Property::where('agent_id', $id)->latest()->get();
        return view('agent.property.all_property', compact('property'));
    } //End Method

    public function AgentAddProperty(){

        $propertyType = PropertyType::latest()->get();
        $amenities = Amenities::latest()->get();
        return view('agent.property.add_property', compact('propertyType', 'amenities'));
    } //End Method

    public function AgentStoreProperty(Request $request) {
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
            'agent_id' => Auth::user()->id,
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
        return redirect()->route('agent.all.property')->with($notification);

    } //End Method

    public function AgenteditProperty($id){
        $property = Property::findOrFail($id);

        $data = $property->amenities_id;        
        $ame_data = explode(',', $data);      //Convert string to array && Show all selected value

        $facilities = Facility::where('property_id', $id)->get();
        $multiImage = MultiImage::where('property_id', $id)->get();
        $propertyType = PropertyType::latest()->get();
        // dd($property->property_status);
        $amenities = Amenities::latest()->get();
        return view('agent.property.edit_property', compact('property', 'propertyType', 'amenities', 'ame_data', 'multiImage', 'facilities'));
    } //End Method

    public function UpdateAgentProperty(Request $request){
        $data = $request->amenities_id;
        $amenities = implode(',', $data);
        $get_id = $request->id;

        Property::findOrFail($get_id)->update([
            'ptype_id' => $request->property_id,
            'amenities_id' => $amenities,
            'property_name' => $request->property_name,
            'property_slug' => Str::lower(str_replace(' ', '-', $request->property_name)).'-'.random_int(10000, 99999),
            'property_status' => $request->property_status,

            'lowest_price' => $request->lowest_price,
            'max_price' => $request->max_price,
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
            'agent_id' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => "Property Updated Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('agent.all.property')->with($notification);
    }

    public function UpdateAgentpropertyThambnail(Request $request){
        $property_id = $request->id;
        $oldImage = $request->old_img;

        if(file_exists('uploads/property/thambnail/'.$oldImage)){
            unlink('uploads/property/thambnail/'.$oldImage);
        }

        $img = $request->property_thambnail;
        $extension = $img->extension();
        $file_name = uniqid().'.'.$extension;

        // create new manager instance with desired driver
        $manager = new ImageManager(new Driver());
        $image = $manager->read($img);
        $image->resize(200, 150);
        $image->save('uploads/property/thambnail/'.$file_name);


        Property::findOrFail($property_id)->update([
            'property_thambnail' => $file_name,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => "Property Image Thambnail Updated Successfully",
            'alert-type' => 'success'
        );
        return redirect()->route('agent.all.property')->with($notification);
    } //End Method

    public function UpdateAgentpropertyMultiImage(Request $request){
        $imgs = $request->multi_img;

        if($imgs){
            foreach($imgs as $id => $img){
                $imgDel = MultiImage::findOrFail($id);
                $oldImage = $imgDel->photo_name;
                if(file_exists('uploads/property/multi_img/'.$oldImage)){
                    unlink('uploads/property/multi_img/'.$oldImage);
                }

                $extensions = $img->extension();
                $multi_name = uniqid().'.'.$extensions;

                // create new manager instance with desired driver
                $managers = new ImageManager(new Driver());
                $imagei = $managers->read($img);
                $imagei->resize(770, 520);
                $imagei->save('uploads/property/multi_img/'.$multi_name);

                MultiImage::where('id', $id)->update([
                    'photo_name' => $multi_name,
                    'updated_at' => Carbon::now(),
                ]);
            } //End foreach

            $notification = array(
                'message' => "Property Multi Image Updated Successfully",
                'alert-type' => 'success'
            );
            return redirect()->route('agent.all.property')->with($notification);
        }else{
            $notification = array(
                'message' => "Nothing To Update",
                'alert-type' => 'error'
            );
            return redirect()->route('agent.all.property')->with($notification);
        } //End else
    } //End Method

    public function UpdateAgentpropertyFacilities(Request $request){
        $property_id = $request->id;

        if($request->facility_name == NULL){
            $notification = array(
                'message' => "You Have To Add Facility Name",
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }else{
            Facility::where('property_id', $property_id)->delete();

            $facilities = count($request->facility_name);
            for($i=0; $i < $facilities; $i++){
                $cnt = new Facility();
                $cnt->property_id = $property_id;
                $cnt->facility_name = $request->facility_name[$i];
                $cnt->distance = $request->distance[$i];
                $cnt->save();
            }

            $notification = array(
                'message' => "Property Facilities Updated Successfully",
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } //End else
    } //End Method

    public function AddAgentnewMultiImage(Request $request) {
        $property_id = $request->property_id;
        $images = $request->file('multi_img');
        if($images){
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

            $notification = array(
                'message' => "New Property Multi Image Added Successfully",
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        }else{
            $notification = array(
                'message' => "Nothing To Update",
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        } //End else
    } //End Method

    public function DeleteAgentpropertyMultiImage($id){
        $oldimg = MultiImage::findOrFail($id);
        $img = $oldimg->photo_name;
        if(file_exists('uploads/property/multi_img/'.$img)){
            unlink('uploads/property/multi_img/'.$img);
        }

        MultiImage::findOrFail($id)->delete();

        $notification = array(
            'message' => "Property Multi Image Deleted Successfully",
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    } //End Method

    public function DetailsAgentproperty($id){
        $property = Property::findOrFail($id);
        $data = $property->amenities_id;        
        $ame_data = explode(',', $data);      //Convert string to array && Show all selected value

        $facilities = Facility::where('property_id', $id)->get();
        $multiImage = MultiImage::where('property_id', $id)->get();
        $propertyType = PropertyType::latest()->get();
        // dd($property->property_status);
        $amenities = Amenities::latest()->get();
        return view('agent.property.details_property', compact('property', 'propertyType', 'amenities', 'ame_data', 'multiImage', 'facilities'));
    } //End Method

    public function AgentpropertyStatus($id){
        $property = Property::findOrFail($id);
        if($property->status == 1){
            Property::findOrFail($id)->update([
                'status' => 0,
            ]);
            $notification = array(
                'message' => "Agent Property Deactivated Successfully",
                'alert-type' => 'success'
            );
            return redirect()->route('agent.all.property')->with($notification);
        }else{
            Property::findOrFail($id)->update([
                'status' => 1,
            ]);
            $notification = array(
                'message' => "Agent Property Activated Successfully",
                'alert-type' => 'success'
            );
            return redirect()->route('agent.all.property')->with($notification);
        }
    } //End Method

    public function BuyPackage(){
        return view('agent.package.buy_package');
    } //End Method
}
