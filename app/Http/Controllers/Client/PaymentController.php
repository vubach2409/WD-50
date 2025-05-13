<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Orders;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Shipping;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Thanh toán

    public function PaymentOnline(Request $request){
                if (isset($_POST['cod'])) {
                    // Xử lý thanh toán COD
                    $request->validate([
                        'consignee_address' => ['required', 'string', 'max:255'],
                        'consignee_name' => ['required', 'string', 'max:255'],
                        'consignee_phone' => ['required', 'string', 'max:10'],
                        'shipping_id' => ['required', 'integer', 'exists:shippings,id'],
                        'email' => ['required', 'email', 'max:255'],
                        'city' => ['required', 'string', 'max:255'],
                        'subdistrict' => ['required', 'string', 'max:255'],
                    ]);
                
                    $userId = Auth::id();
                    $cartItems = Carts::where('user_id', $userId)->with(['product', 'variant'])->get();
                
                    if ($cartItems->isEmpty()) {
                        return redirect()->route('cart')->with('error', 'Giỏ hàng trống!');
                    }
                
                    $shipping = Shipping::findOrFail($request->shipping_id);
                
                    // Tính tổng tiền dựa trên biến thể nếu có
                    $totalPrice = $cartItems->sum(function ($item) {
                        return $item->quantity * $item->variant->price;
                    });
                    $voucher = session('voucher');

                    $discountAmount = 0;

                    if ($voucher && isset($voucher['type'])) {
                        if ($voucher['type'] === 'percent' && isset($voucher['percent'])) {
                            $discountAmount = $totalPrice * ($voucher['percent'] / 100);
                        } elseif ($voucher['type'] === 'fixed' && isset($voucher['discount'])) {
                            $discountAmount = $voucher['discount'];
                        }
                    }

                    
                    $finalTotal = $totalPrice - $discountAmount + $shipping->fee;
                    
                
                    DB::beginTransaction();
                
                    try {
                        $order = Orders::create([
                            'user_id' => $userId,
                            'total' => $finalTotal,
                            'shipping_id' => $shipping->id,
                            'shipping_fee' => $shipping->fee,
                            'payment_method' => 'cod',
                            'status' => 'pending',
                            'consignee_address' => $request->consignee_address,
                            'subdistrict' => $request->subdistrict,
                            'city' => $request->city,
                            'email' => $request->email,
                            'consignee_name' => $request->consignee_name,
                            'consignee_phone' => $request->consignee_phone,
                            'transaction_id' => now()->timestamp . $userId,
                            'voucher_code' => $voucher['code'] ?? null,
                            'discount_amount' => $discountAmount,
                        ]);
                     
                        Payment::create([
                            'order_id' => $order->id,
                            'user_id' => $userId,
                            'payment_method' => 'cod',
                            'amount' => $finalTotal,
                            'status' => 'pending',
                            'transaction_id' => $order->transaction_id
                        ]);
                
                        foreach ($cartItems as $item) {
                            $price = $item->variant->price;
                            $variant = $item->variant; 
                            $productName = $item->product ? $item->product->name : 'N/A'; 
                            $variantName = $variant ? $variant->variation_name : null;
                            $variantSku = $variant ? $variant->sku : null;
                            $colorName = $variant && $variant->color ? $variant->color->name : null;
                            $sizeName = $variant && $variant->size ? $variant->size->name : null;
                        
                            $originalImage = $variant ? $variant->image : null;
                            $variantImage = null;
                            
                            if ($originalImage && Storage::disk('public')->exists($originalImage)) {
                                $newImageName = 'order_detail/' . time() . '_' . basename($originalImage);
                                Storage::disk('public')->copy($originalImage, $newImageName);
                                $variantImage = $newImageName;
                            }
                            OrderDetail::create([
                                'order_id' => $order->id,
                                'product_id' => $item->product_id,  
                                'product_name' => $productName,    
                                'variant_id' => $item->variant_id,
                                'variant_name' => $variantName,
                                'variant_sku' => $variantSku,
                                'color_name' => $colorName,
                                'size_name' => $sizeName,
                                'variant_image' => $variantImage, 
                                'price' => $price,
                                'quantity' => $item->quantity,
                            ]);
                
                            // Trừ tồn kho biến thể hoặc sản phẩm
                            if ($item->variant) {
                                $variant = $item->variant;
                                $variant->stock -= $item->quantity;
                                $variant->save();
                                
                            } 
                        }
                
                        // Xoá giỏ hàng
                        Carts::where('user_id', $userId)->delete();
                        
                        session()->forget('voucher');
                        // Trừ số lần sử dụng mã giảm giá
                        if ($voucher && isset($voucher['code'])) {
                            $voucherModel = Voucher::where('code', $voucher['code'])->first();
                            if ($voucherModel && $voucherModel->usage_limit > 0) {
                                $voucherModel->decrement('usage_limit');
                            }

                            // Tăng số lượt dùng mã giảm giá nếu có
                            $voucherModel = Voucher::where('code', $voucher['code'])->first();
                            if ($voucherModel) {
                                $voucherModel->used += 1;
                                $voucherModel->save();
                            }
                           }

                        DB::commit();
                
                        return redirect()->route('thankyou')->with('success', 'Đặt hàng thành công, chúng tôi sẽ liên hệ với bạn sớm nhất!');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return redirect()->route('thankyou')->with('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau.');
                    }
                    
                } else {
                    // Xử lý thanh toán VNPAY
                    $request->validate([
                        'consignee_address' => ['required', 'string', 'max:255'],
                        'consignee_name' => ['required', 'string', 'max:255'],
                        'consignee_phone' => ['required', 'string', 'max:10'],
                        'shipping_id' => ['required', 'integer', 'exists:shippings,id'],
                        'email' => ['required', 'string', 'max:255'],
                        'city' => ['required', 'string', 'max:255'],
                        'subdistrict' => ['required', 'string', 'max:255'],
                    ]);
                    $userId = Auth::id();
                    $cartItems = Carts::where('user_id', $userId)->with(['product', 'variant'])->get();
                    if ($cartItems->isEmpty()) {
                        return redirect()->route('cart')->with('error', 'Giỏ hàng trống!');
                    }
                
                    $shipping = Shipping::findOrFail($request->shipping_id);
                
                    // Tính tổng tiền dựa trên biến thể nếu có
                    $totalPrice = $cartItems->sum(function ($item) {
                        return $item->quantity * ($item->variant ? $item->variant->price : $item->product->price);
                    });
                            if (!$shipping) {
                                return redirect()->back()->with('error', 'Phương thức vận chuyển không hợp lệ.');
                            }
                            $voucher = session('voucher');
                    $discountAmount = 0;

                    if ($voucher && isset($voucher['type'])) {
                        if ($voucher['type'] === 'percent' && isset($voucher['percent'])) {
                            $discountAmount = $totalPrice * ($voucher['percent'] / 100);
                        } elseif ($voucher['type'] === 'fixed' && isset($voucher['discount'])) {
                            $discountAmount = $voucher['discount'];
                        }
                    }

                    $finalTotal = $totalPrice + $shipping->fee - $discountAmount;

                    if(isset($_POST['redirect'])){
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                        $vnp_Returnurl = route('thanks.vnpay');
                        $vnp_TmnCode = "TKKV0ZIT";//Mã website tại VNPAY 
                        $vnp_HashSecret = "IW37H3W2QZ4UHXJYWORIKP87X6OCGDSA"; //Chuỗi bí mật
                        
                        $vnp_TxnRef = $vnp_TxnRef = now()->timestamp . Auth::id(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                        $vnp_OrderInfo = json_encode([
                            'name' => $request->consignee_name,
                            'phone' => $request->consignee_phone,
                            'address' => $request->consignee_address,
                            'shipping' => $request->shipping_id,
                            'email' => $request->email,
                            'city' => $request->city,
                            'subdistrict' => $request->subdistrict,
                            'voucher_code' => $voucher['code'] ?? null,
                            'discount_amount' => $discountAmount,
                        ]);
                        $vnp_OrderType = "billpayment";
                        $vnp_Amount = $finalTotal * 100; // giá
                        $vnp_Locale = "vn";
                        // $vnp_BankCode = "NCB";
                        $vnp_IpAddr = request()->ip();
                    
                        $inputData = array(
                            "vnp_Version" => "2.1.0",
                            "vnp_TmnCode" => $vnp_TmnCode,
                            "vnp_Amount" => $vnp_Amount,
                            "vnp_Command" => "pay",
                            "vnp_CreateDate" => date('YmdHis'),
                            "vnp_CurrCode" => "VND",
                            "vnp_IpAddr" => $vnp_IpAddr,
                            "vnp_Locale" => $vnp_Locale,
                            "vnp_OrderInfo" => $vnp_OrderInfo,
                            "vnp_OrderType" => $vnp_OrderType,
                            "vnp_ReturnUrl" => $vnp_Returnurl,
                            "vnp_TxnRef" => $vnp_TxnRef,
                        );
                        
                        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                            $inputData['vnp_BankCode'] = $vnp_BankCode;
                        }
                        // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                        //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                        // }
                        
                        //var_dump($inputData);
                        ksort($inputData);
                        $query = "";
                        $i = 0;
                        $hashdata = "";
                        foreach ($inputData as $key => $value) {
                            if ($i == 1) {
                                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                            } else {
                                $hashdata .= urlencode($key) . "=" . urlencode($value);
                                $i = 1;
                            }
                            $query .= urlencode($key) . "=" . urlencode($value) . '&';
                        }
                        
                        $vnp_Url = $vnp_Url . "?" . $query;
                        if (isset($vnp_HashSecret)) {
                            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                        }
                        $returnData = array('code' => '00'
                            , 'message' => 'success'
                            , 'data' => $vnp_Url);
                            if (isset($_POST['redirect'])) {
                                header('Location: ' . $vnp_Url);
                                die();
                            } else {
                                echo json_encode($returnData);
                            }
                    }
                    
                }
            
        
    }
                   
    // xử lý lưu đơn hàng và giao dịch sau khi thanh toán vnpay
        public function xuly(Request $request){
                DB::beginTransaction();
            
                try {
                    // 1. Nhận dữ liệu từ VNPAY
                    $vnp_TxnRef = $request->input('vnp_TxnRef');
                    $vnp_ResponseCode = $request->input('vnp_ResponseCode');
                    $vnp_OrderInfo = json_decode($request->input('vnp_OrderInfo'), true);
            
                    if ($vnp_ResponseCode !== "00") {
                        return redirect()->route('thankyou')->with('error', 'Đặt hàng thất bại!');
                    }
            
                    // 2. Xác minh người dùng
                    $user = Auth::user();
                    if (!$user) {
                        return redirect()->route('thankyou')->with('error', 'Bạn chưa đăng nhập!');
                    }
            
                    // 3. Lấy giỏ hàng
                    $cartItems = Carts::where('user_id', $user->id)->with('product', 'variant')->get();
                    if ($cartItems->isEmpty()) {
                        return redirect()->route('cart')->with('error', 'Giỏ hàng trống!');
                    }
            
                    // 4. Lấy thông tin vận chuyển
                    $shipping = Shipping::find($vnp_OrderInfo['shipping']);
                    if (!$shipping) {
                        return redirect()->route('thankyou')->with('error', 'Thông tin vận chuyển không hợp lệ!');
                    }
            
                    // 5. Tính tổng tiền
                    // Tính tổng tiền dựa trên biến thể nếu có
                    $totalPrice = $cartItems->sum(function ($item) {
                        return $item->quantity * $item->variant->price;
                    });
                    $voucher = session('voucher');

                    $discountAmount = 0;

                    if ($voucher && isset($voucher['type'])) {
                        if ($voucher['type'] === 'percent' && isset($voucher['percent'])) {
                            $discountAmount = $totalPrice * ($voucher['percent'] / 100);
                        } elseif ($voucher['type'] === 'fixed' && isset($voucher['discount'])) {
                            $discountAmount = $voucher['discount'];
                        }
                    }

                    
                    $finalTotal = $totalPrice - $discountAmount + $shipping->fee;

            
                    // 6. Tạo đơn hàng
                    $order = Orders::create([
                        'transaction_id' => $vnp_TxnRef,
                        'user_id' => $user->id,
                        'total' => $finalTotal,
                        'shipping_fee' => $shipping->fee,
                        'payment_method' => 'vnpay',
                        'consignee_name' => $vnp_OrderInfo['name'],
                        'subdistrict' => $vnp_OrderInfo['subdistrict'],
                        'email' => $vnp_OrderInfo['email'],
                        'city' => $vnp_OrderInfo['city'],
                        'consignee_phone' => $vnp_OrderInfo['phone'],
                        'consignee_address' => $vnp_OrderInfo['address'],
                        'status' => 'pending',
                        'shipping_id' => $shipping->id,
                        'voucher_code' => $vnp_OrderInfo['voucher_code'] ?? null,
                        'discount_amount' => $vnp_OrderInfo['discount_amount'] ?? 0,

                    ]);
            
                    // 7. Ghi giao dịch và thanh toán
                    Transaction::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'transaction_id' => $vnp_TxnRef,
                        'amount' => $totalPrice,
                        'payment_method' => 'vnpay',
                        'status' => 'success',
                        'response_code' => $vnp_ResponseCode,
                        'response_message' => 'Thanh toán thành công',
                    ]);
            
                    Payment::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'payment_method' => 'vnpay',
                        'amount' => $finalTotal,
                        'status' => 'success',
                        'transaction_id' => $vnp_TxnRef,
                    ]);
            
                    // 8. Lưu từng sản phẩm vào OrderDetail + trừ kho
                    foreach ($cartItems as $item) {
<<<<<<< Updated upstream
                        $price = $item->variant->price;
                        $variant = $item->variant; 
                        $productName = $item->product ? $item->product->name : 'N/A'; 
                        $variantName = $variant ? $variant->variation_name : null;
                        $variantSku = $variant ? $variant->sku : null;
                        $colorName = $variant && $variant->color ? $variant->color->name : null;
                        $sizeName = $variant && $variant->size ? $variant->size->name : null;
                        $originalImage = $variant ? $variant->image : null;
                        $variantImage = null;
                        
                        if ($originalImage && Storage::disk('public')->exists($originalImage)) {
                            $newImageName = 'order_detail/' . time() . '_' . basename($originalImage);
                            Storage::disk('public')->copy($originalImage, $newImageName);
                            $variantImage = $newImageName;
                        }
                    
=======
                        // Lưu chi tiết đơn hàng
                        $price = $item->variant->price;
>>>>>>> Stashed changes
                        OrderDetail::create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,  
                            'product_name' => $productName,    
                            'variant_id' => $item->variant_id,
                            'variant_name' => $variantName,
                            'variant_sku' => $variantSku,
                            'color_name' => $colorName,
                            'size_name' => $sizeName,
                            'variant_image' => $variantImage, // 👈 Vẫn là cột variant_image
                            'price' => $price,
                            'quantity' => $item->quantity,
<<<<<<< Updated upstream
                        ]);
=======
                            'price' => $price,
                        ]);
                        
            
>>>>>>> Stashed changes
                        // Trừ tồn kho theo variant nếu có
                        if ($item->variant_id) {
                            $variant = ProductVariant::where('id', $item->variant_id)->lockForUpdate()->first();
            
                            if (!$variant || $variant->stock < $item->quantity) {
                                DB::rollBack();
                                return redirect()->route('cart')->with('error', 'Biến thể sản phẩm "' . $item->product->name . '" không đủ hàng trong kho!');
                            }
            
                            $variant->stock -= $item->quantity;
                            $variant->save();
            
            
                        }
                    }
            
                    // 9. Xoá giỏ hàng
                    Carts::where('user_id', $user->id)->delete();
                    session()->forget('voucher');

                     //Trừ số lần sử dụng mã giảm giá
                     if ($voucher && isset($voucher['code'])) {
                        $voucherModel = Voucher::where('code', $voucher['code'])->first();
                        if ($voucherModel && $voucherModel->usage_limit > 0) {
                            $voucherModel->decrement('usage_limit');
                        }
                    }  
                                        // Tăng số lượt dùng mã giảm giá nếu có
                    if (!empty($vnp_OrderInfo['voucher_code'])) {
                        $voucher = Voucher::where('code', $vnp_OrderInfo['voucher_code'])->lockForUpdate()->first();
                        
                        if ($voucher) {
                            $voucher->used += 1;
                            $voucher->save();
                        }
                    }

                    DB::commit();
            
                    return redirect()->route('thankyou')->with('success', 'Đặt hàng thành công, chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->route('thankyou')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
                }
            }
            


    // end xử lý

    // thankyou
                public function thankyou(){
                    return view('client.thankyou');
                }
    // end thankyouu
}

              


