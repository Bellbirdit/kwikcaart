<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Product;

class ReviewController extends Controller
{
    public function review_add(Request $request)
    {
        $review = new Review();
        $review->product_id = $request->product_id;
        $review->name = $request->name;
        $review->email = $request->email;
        $review->rating = $request->rating;
        $review->review = $request->review;
        if ($review->save()) {
            return response()->json(['status' => 'success', 'msg' => 'Thank you for your feedback']);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'Try again']);

        }
    }

    public function reviews_list(Request $request)
    {
        $html = "";
        $width = 0;
        $reviews = Review::where('product_id', $request->product_id)->where('status', 1)->get();

        foreach ($reviews as $review) {
            if ($review->rating == '1') {
                $width = 20;
            } elseif ($review->rating == '2') {
                $width = 40;
            } elseif ($review->rating == '3') {
                $width = 60;
            } elseif ($review->rating == '4') {
                $width = 80;
            } elseif ($review->rating == '5') {
                $width = 100;
            }
            $html .= '

            <div class="single-comment justify-content-between d-flex mb-30">
            <div class="user justify-content-between d-flex">
                <div class="thumb text-center">
                    <img src="' . asset('assets/imgs/blog/author-2.png') . '"
                        alt="" /><br>
                    <a href="#"
                        class="font-heading text-brand">' . ucwords($review->name) . '</a>
                </div>
                <div class="desc">
                    <div
                        class="d-flex justify-content-between mb-10">
                        <div class="d-flex align-items-center">
                            <span
                                class="font-xs text-muted">' . $review->created_at->format('M d , Y h:m a') . ' </span>
                        </div>


                    </div>
                    <p class="mb-10">  ' . ucwords($review->review) . '  </p>
                </div>
            </div>
            <div class="product-rate d-inline-block">
                        <div class="product-rating" style="width: ' . $width . '%"></div>
                    </div>
        </div>

            ';
        }
        return response()->json(['status' => 'success', 'html' => $html]);

    }

    public function review_delete($id)
    {
        $review = Review::find($id);
        $review->delete();
        return response()->json(['status' => 'success']);

    }

    public function reviews_status(Request $request)
    {
        if ($request->status == 1) {
            $status = 0;
            $st = "Activate";
        } else {
            $st = "De Activate";
            $status = 1;
        }

        $reviews = Review::where('product_id', $request->id)->get();

        foreach ($reviews as $review) {

            $r = Review::find($review->id);

            $r->status = $status;
            $r->save();
        }

        return response()->json(['status' => 'success', 'msg' => 'Review ' . $st . ' Successfully ']);

    }

    public function getReviews(Request $request)
    {
        $reviews = Review::query();
        $reviews = $reviews->with('product');
        if($request->q){
            $reviews->leftJoin('products as p',
                        function ($join) {
                            $join->on('p.id', '=', 'reviews.product_id');
                        })
                        ->where('p.name', 'like', "%{$request->q}%");
            // $reviews = $reviews->where('review', 'like', "%{$request->q}%");
            // $reviews = $reviews->where('product.name', 'like', "%{$request->q}%");
        }
        if($request->name){
            $reviews = $reviews->where('name', 'like', "%{$request->name}%");
        }
        if($request->status == "Active"){
            $reviews = $reviews->where('status', 1);
        }
        if($request->status == "Disabled"){
            $reviews = $reviews->where('status', 0);
        }
        
        $reviews = $reviews->get();
        if (isset($reviews) && sizeof($reviews) > 0) {
        $html = "";
      
        foreach ($reviews as $review) {
            $product = Product::where('id',$review->product_id)->pluck('name')->first();
            if ($review->rating == '1') {
                $width = 20;
            } elseif ($review->rating == '2') {
                $width = 40;
            } elseif ($review->rating == '3') {
                $width = 60;
            } elseif ($review->rating == '4') {
                $width = 80;
            } elseif ($review->rating == '5') {
                $width = 100;
            }

            if ($review->status == '1') {
                $checked = 'checked';
            } else {
                $checked = '';
            }
            $html .= '

            <tr>
           
            <td></td>
            <td><b>'.$product.'</b></td>
            <td>'.$review->name.'</td>
            <td>
                <ul class="rating-stars">
                    <li style="width: '.$width.'%" class="stars-active">
                        <img src="'.asset('assets/imgs/icons/stars-active.svg').'" alt="stars" />
                    </li>
                    <li>
                        <img src="'.asset('assets/imgs/icons/starts-disable.svg').'" alt="stars" />
                    </li>
                </ul>
            </td>
            <td>'.$review->created_at->format('d M, Y').'</td>

            <td>
            <label class="switch">
            <input type="checkbox"' . $checked . ' data-id="' . $review->id . '" class="reviewStatus" >
                <span class="slider round"></span>
            </label>
            </td>
            <td class="text-end">
               
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm">
                        <i class="material-icons md-more_horiz"></i> </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">View detail</a>
                        <a class="dropdown-item" href="#">Edit info</a>
                        <a class="dropdown-item text-danger delete_review" href="javascript:;"  id='.$review->id.' >Delete</a>
                    
                    </div>
                </div>
               
            </td>
        </tr>

            ';
        }

        return response(['status' => 'success', 'rows' => $html]);
    } else {

        return response(['status' => 'fail']);
    }
    }


    public function changeReviewstatus(Request $request)
    {

    $review_status = Review::find($request->data_id);
    $review_status->status = $request->status;
    $review_status->save();
    return response()->json(['status' => 'success', 'msg' => 'Review status has been changed successfully']);
}
}
