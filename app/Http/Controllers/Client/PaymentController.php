<?php

namespace App\Http\Controllers\Client;

use App\Models\Carts;
use App\Models\Orders;
use App\Models\OrderDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    // Thanh toán
            public function CodPayment(Request $request){
                if(isset($_POST['cod'])){
                    //validate trước khi nhận dữ liệu
                    $request->validate([
                        'consignee_address' => ['required', 'string', 'max:255'],
                        'consignee_name' => ['required', 'string', 'max:255'],
                        'consignee_phone' => ['required', 'string', 'max:10'],
                    ]);
            
                    DB::beginTransaction();
            
                    try {
                        $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
                        $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
            
                        // 🛒 Tạo đơn hàng
                        $order = Orders::create([
                            'user_id' => Auth::id(),
                            'total' => $totalPrice,
                            'consignee_address' => $request->consignee_address,
                            'payment_method' => 'cod',
                            'consignee_name' => $request->consignee_name,
                            'consignee_phone' => $request->consignee_phone,   
                            'status' => 'pending',
                            'transaction_id' =>  now()->timestamp . Auth::id(),
                        ]);
                   
                        // thêm sản phẩm vào đơn hàng
                        foreach ($cartItems as $item) {
                            OrderDetail::create([
                                'order_id' => $order->id,
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'price' => $item->product->price
                            ]);
                        }
                        
            
                        // 🗑️ Xóa giỏ hàng sau khi đặt hàng thành công
                        Carts::where('user_id', Auth::id())->delete();
            
                        DB::commit();
            
                        return redirect()->route('thankyou')->with('success', 'Đặt hàng thành công, chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return redirect()->route('thankyou')->with('error', 'Đặt hàng thất bại!');
                    }
                }           
            }
    //End thanh toán cod

    // Thanh toán vnpay
            public function vnpayPayment(Request $request){
                // validate trước khi nhận dữ liệu
                $request->validate([
                    'consignee_name' => 'required|string|max:255',
                    'consignee_phone' => 'required|string|max:20',
                    'consignee_address' => 'required|string|max:255',
                ]);

                $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
                $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

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
                    ]);
                    $vnp_OrderType = "billpayment";
                    $vnp_Amount = $totalPrice * 100; // giá
                    $vnp_Locale = "vn";
                    $vnp_BankCode = "NCB";
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

    // end thanh toán vnpay
       
    // xử lý lưu đơn hàng và giao dịch sau khi thanh toán vnpay
        public function xuly(Request $request){
            DB::beginTransaction(); // Bắt đầu transaction để đảm bảo toàn vẹn dữ liệu
                
            try {
                // Nhận dữ liệu từ VNPay
                $vnp_TxnRef = $request->input('vnp_TxnRef');
                $vnp_ResponseCode = $request->input('vnp_ResponseCode');
                $vnp_OrderInfo = json_decode($request->input('vnp_OrderInfo'), true);
        
                // Kiểm tra thanh toán thành công
                      
        if ($vnp_ResponseCode != "00") {
           return redirect()->route('thankyou')->with('error', 'Đặt hàng thất bại!');
       }

       $user = Auth::user();
       if (!$user) {
           return redirect()->route('thankyou')->with('error', 'Bạn chưa đăng nhập!');
       }

       $cartItems = Carts::where('user_id', Auth::id())->with('product')->get();
       if ($cartItems->isEmpty()) {
           return redirect()->route('thankyou')->with('error', 'Giỏ hàng trống!');
       }

       $totalPrice = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
                        // Lưu vào bảng orders
                        $order = Orders::create([
                            'transaction_id' => $vnp_TxnRef,
                            'user_id' => $user->id,
                            'total' => $totalPrice,
                            'payment_method' => 'vnpay',
                            'consignee_name' => $vnp_OrderInfo['name'],
                            'consignee_phone' => $vnp_OrderInfo['phone'],
                            'consignee_address' => $vnp_OrderInfo['address'],
                            'status' => 'completed'
                        ]);

                        Transaction::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'transaction_id' => $vnp_TxnRef,
                            'amount' => $totalPrice,
                            'payment_method' => 'vnpay',
                            'status' => 'success',
                            'response_code' => $vnp_ResponseCode,
                            'response_message' => 'Thanh toán thành công'
                        ]);
                
                        // Lưu vào bảng order_items
                        foreach ($cartItems as $item) {
                            OrderDetail::create([
                                'order_id' => $order->id,
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                                'price' => $item->product->price
                            ]);
                        }
                
                        // Xóa giỏ hàng sau khi đặt hàng thành công
                        Carts::where('user_id', Auth::id())->delete();
                
                        DB::commit(); // Xác nhận giao dịch
                
                        return redirect()->route('thankyou')->with('success', 'Đặt hàng thành công, chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất!');
                    } catch (\Exception $e) {
                        DB::rollBack(); // Nếu có lỗi, rollback dữ liệu
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

              


