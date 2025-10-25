<?php

namespace App\Http\Controllers\Fontend;

use App\Http\Controllers\Controller;
use App\Models\Compare;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    public function AddToCompare(Request $request, $property_id){
        if (auth()->check()) {

            // Check if the property is already in the wishlist
            $exists = Compare::where('user_id', Auth::id())
                    ->where('property_id', $property_id)
                    ->exists();

            if ($exists) {
                return response()->json(['error' => 'Property already in Compare list']);
            }

            Compare::create([
                'user_id' => Auth::id(),
                'property_id' => $property_id,
                'created_at' => Carbon::now(),
            ]);

            return response()->json(['success' => 'Property added to Compare list']);
        } else {
            return response()->json(['error' => 'You must be logged in to add to Compare list']);
        }
    }  // End Method

    public function UserCompare(){
        return view('frontend.compare.user_compare');
    } // End Method

    public function GetCompareProperty(){
        $compare = Compare::with('property')->where('user_id', Auth::id())->latest()->get();
        return response()->json($compare);
    } // End Method

    public function RemoveCompare($id){
        Compare::where('user_id', Auth::id())->where('id', $id)->delete();
        return response()->json(['success' => 'Property removed from Compare list']);
    } // End Method
}
