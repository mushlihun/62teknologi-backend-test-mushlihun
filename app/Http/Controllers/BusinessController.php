<?php

namespace App\Http\Controllers;

use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        // $busines = new Business();
        // $searchParams = $request->all();
        // $business = $busines->getBusinesses($searchParams);
        // return BusinessResource::collection($data);
        $search = $request->json()->all();

        $arrSearch = [];

        if(isset($search['name'])&&$search['name'] != "" && $search['name']!=null){
            $arrName = ['name','like','%'.$search['name'].'%'];
            $arrSearch[] = $arrName;
        }
        if(isset($search['phone'])&&$search['phone'] != "" && $search['phone']!=null){
            $arrPhone = ['phone','like','%'.$search['phone'].'%'];
            $arrSearch[] = $arrPhone;
        }
        if(isset($search['price'])&&$search['price'] != "" && $search['price']!=null){
            $arrPrice = ['price',$search['priceCond'],$search['price']];
            $arrSearch[] = $arrPrice;
        }
        if(isset($search['rating'])&&$search['rating'] != "" && $search['rating']!=null){
            $arrPrice = ['rating',$search['ratingCond'],$search['rating']];
            $arrSearch[] = $arrPrice;
        }
        if(isset($search['categories'])&&$search['categories'] != "" && $search['categories']!=null){
            $business = Business::where($arrSearch)->whereIn('categories_id', $search['categories'])->paginate(10);
        }else{
            $business = Business::where($arrSearch)->paginate(10);
        }

        return response()->json(['messages' => 'success', 'data'=>$business], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBusinessRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_post = $request->json()->all();
        $business = new Business();
        $validation = Validator::make($data_post, [
            'alias' => 'required',
            'name' => 'required',
            'image_url' => 'required',
            'is_closed' => 'required|boolean',
            'review_count' => 'required|numeric',
            'categories' => 'required',
            'rating' => 'required|numeric',
            'coordinates' => 'required',
            'transactions' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
            'phone' => 'required',
            'distance' => 'required',

        ], [
            'required' => 'The :attribute field is required',
        ]);
        
        if($validation->fails()){
            return response()->json([
                'status' => false,
                'message' => $validation->messages()
            ], 400);
        }
        
        $base64_image = $data_post['image_url'];
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $filename = uniqid().".png";
            $data = base64_decode($data);
            Storage::disk('public')->put($filename, $data, 'public');
            $data_post['image_url'] = $filename;
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Image Type',
            ], 400);
        }
        $data = [
            'alias' => Str::slug($request->alias),
            'name' => $request->name,
            'image_url' => $request->image_url,
            'url' => $request->url,
            'is_closed' => false,
            'review_count' => $request->review_count,
            'categories' => json_encode($request->categories),
            'rating' => $request->rating,
            'coordinates' => json_encode($request->coordinates),
            'transactions' => json_encode($request->transactions),
            'price' => $request->price,
            'location' => json_encode($request->location),
            'phone' => $request->phone,
            'distance' => $request->distance,
        ];
        $data = $business->insertData($data_post);
        $businesses = Businesses::create($data);
        return response()->json([
            'status' => true,
            'message' => 'Business created successfully',
            "businesses" => $businesses
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $business = new Business();
        $data = $business->getBusinessDetail($id);
        return response()->json([
            
            'status' => 'success',
            'data' => $data
        ],200);
    }

    public function showslug($slug)
    {
        $business = new Business();
        $data = $business->getBusinessDetailSlug($slug);
        return response()->json([
            
            'status' => 'success',
            'data' => $data
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBusinessRequest  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data_post = $request->all();

        $validation = Validator::make($data_post, [
            'name' => 'required',
            'categories' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
            'coordinates' => 'required',
        ], [
            'required' => 'The :attribute field is required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()
            ], 400);
        }
        $business = Business::find($id);

        if (!$business) {
            return response()->json([
                'status' => 'error',
                'message' => 'Business not found',
            ], 400);
        }

        $base64_image = $data_post['image'];
        if(!empty($base64_image)) {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
                $filename = uniqid().".png";
                $data = base64_decode($data);
                Storage::disk('public')->put($filename, $data);
                $data_post['image_url'] = $filename;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Image Type',
                ], 400);
            }
        }
        
        $business_model = new Business();
        $resp = $business_model->updateData($data_post,$id);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Update Business',
        ], 200);
    }

    // public function update(Request $request)
    // {
    //     $businesses = Business::find($request->id);

    //     $data = [
    //         // 'alias' => Str::slug($request->alias),
    //         'name' => $request->name,
    //         'image_url' => $request->image_url,
    //         'url' => $request->url,
    //         'is_closed' => $request->is_closed,
    //         'review_count' => $request->review_count,
    //         'categories' => json_encode($request->categories),
    //         'rating' => $request->rating,
    //         'coordinates' => json_encode($request->coordinates),
    //         'transactions' => json_encode($request->transactions),
    //         'price' => $request->price,
    //         'location' => json_encode($request->location),
    //         'phone' => $request->phone,
    //         'distance' => $request->distance,
    //     ];

    //     $businesses->update($data);

    //     return response()->json([
    //         "success" => true,
    //         "message" => "updated successfully",
    //         "businesses" => $businesses
    //     ], 200);
    // }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $business = Business::find($id);
        if (!$business) {
            return response()->json([
                'status' => 'error',
                'message' => 'Business Not Found'
            ], 404);
        }
        $business->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Deleted',

        ], 200);
    }

    public function delete(Request $request)
    {
        $businesses = Businesses::find($request->id);

        $businesses->delete();

        return response()->json([
            "success" => true,
            "message" => "deleted successfully",
            "businesses" => $businesses
        ], 200);
    }
    public function fetchDataByParams($field = null, $keyword = null,  $sortBy = null, $limit = null,)
    {
        $dataSort = [
            'alias',
            'name',
            'image_url',
            'url',
            'is_closed',
            'review_count',
            'categories',
            'rating',
            'coordinates',
            'transactions',
            'price',
            'location',
            'phone',
            'display_phone',
            'distance',
        ];

        if (!in_array($sortBy, $dataSort)) {
            return response()->json([
                "success" => false,
                "message" => "the existing data input does not meet the existing data requirements",
                "example" =>  $dataSort,
            ], 404);
        }

        $businesses = Businesses::where($field, 'like', '%' . $keyword . '%')
            ->orderBy($sortBy)
            ->paginate($limit);

        return response()->json([
            "success" => true,
            "message" => "read data by params successfully",
            "businesses" => $businesses
        ], 200);
    }
    public function fetchAllData()
    {

        $businesses = Business::all();

        return response()->json([
            "success" => true,
            "message" => "read all successfully",
            "businesses" => $businesses
        ], 200);
    }
}
