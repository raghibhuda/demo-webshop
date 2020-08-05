<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Exception;

class WebShopController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index(){
        $products = Product::paginate(PAGINATION_LIMIT);
        $cartCount = $this->getCartItemCount();
        $data['products'] = $products;
        $data['cartCount'] = $cartCount;
        return view('index',$data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function addToCart(Request $request){
        try {
            //As there is no user for cart we can identify it with an unique key
            $data = ['product_id'=>$request->product_id,'cart_key'=>'DEFAULT_TEST_KEY'];
            $product = Cart::find($request->product_id);
            if(!empty($product)){
                return [
                    'success' =>false,
                    'message' => 'Product already exits in cart'
                ];
            }else{
                Cart::create($data);
            }
            return [
                'success' =>true,
                'message' => 'Product has been added to cart successfully'
            ];
        }catch (Exception $exception){
            return [
                'success' =>false,
                'message' => 'Failed to add cart'
            ];
        }
    }


    /**
     * @param Request $request
     * @return array
     */
    public function removeFromCart(Request $request){
        try {
            $product = Cart::where(['product_id'=>$request->product_id]);
            if(empty($product)){
                return [
                    'success' =>false,
                    'message' => 'Product not found'
                ];
            }else{
                $product->delete();
            }
            return [
                'success' =>true,
                'message' => 'Product has been remove from cart successfully'
            ];
        }catch (Exception $exception){
            return [
                'success' =>false,
                'message' => 'Failed to add cart'
            ];
        }
    }

    /**
     * @return Factory|View
     */
    public function getAddedProductsFromCart(){
        $products = Cart::select(
            'carts.product_id',
            'products.name',
            'products.price'
        )->leftJoin('products','products.id','=','carts.product_id')
            ->where('carts.cart_key','DEFAULT_TEST_KEY')
            ->get();
        $cartCount = $this->getCartItemCount();
        $data['cartCount'] = $cartCount;
        $data['products'] = $products;
        return view('cart',$data);
    }

    /**
     * @return int
     */
    public function getCartItemCount(){
        return Cart::with('products')->where(['cart_key'=>'DEFAULT_TEST_KEY'])->count();
    }


    /**
     * @return RedirectResponse
     */
    public function placeOrder(){
        try{
            $products = Cart::select(
                'carts.product_id',
                'products.name',
                'products.price'
            )->leftJoin('products','products.id','=','carts.product_id')
                ->where('carts.cart_key','DEFAULT_TEST_KEY')
                ->get();
            if(!empty($products)){
                $products->each(function ($item){
                    $item->total_amount = round($item->price + ($item->price - $item->price*SHIPPING_CHARGE_PERCENTAGE),2);
                    $item->shipping_cost = round($item->price - $item->price*SHIPPING_CHARGE_PERCENTAGE,2);
                });
                $orderData =[];
                $cartProducts = [];
                foreach ($products as $key=>$value){
                    $data['product_id'] = $value->product_id;
                    $data['total_amount'] = $value->total_amount;
                    $data['net_amount'] = $value->price;
                    $data['shipping_cost'] = $value->shipping_cost;
                    $data['quantity'] = 1;
                    array_push($cartProducts,$value->product_id);
                    array_push($orderData,$data);
                }
                DB:: beginTransaction();
                $response  =Order::create($data);
                $cart  = Cart::whereIn('id', $cartProducts)->delete();
                if(!$response || !$cart){
                    DB::rollBack();
                    return redirect()->back()->with(['error'=>'Something went wrong while placing order']);
                }
                DB::commit();
                return redirect()->back()->with(['success'=>'Order has been placed successfully']);
            }else{
                return redirect()->route('index')->with(['error'=>'Products not found in cart']);
            }
        }catch (Exception $exception){
            dd($exception->getMessage());
            return redirect()->back()->with(['error'=>'Something went wrong while placing order']);
        }
    }

}
