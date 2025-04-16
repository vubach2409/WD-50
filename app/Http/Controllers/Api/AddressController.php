<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



class AddressController extends Controller
{
    // API lấy danh sách thành phố
    public function getCities()
    {
        $cities = ['Hà Nội','Hoà Bình','Vĩnh Phúc']; // Ví dụ, lấy từ DB nếu cần
        return response()->json($cities);
    }

    // API lấy danh sách quận theo thành phố
    public function getDistricts(Request $request)
    {
        $city = $request->city;
        
        $districts = [];
        
        if ($city === 'Hà Nội') {
            $districts = ['Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng','Cầu Giấy','Nam Từ Liêm','Hoàng Mai','Đống Đa','Tây Hồ','Thanh Xuân','Bắc Từ Liêm'];
        } elseif ($city === 'Hoà Bình') {
            $districts = [' Lạc Sơn', 'Kim Bôi', 'Lương Sơn','Tân Lạc','Yên Thuỷ','Lạc Thuỷ','Mai Châu'];
        }
        elseif ($city === 'Vĩnh Phúc') {
            $districts = [' Vĩnh Tường', 'Vĩnh Yên', 'Yên Lạc','Púc Yên','Bình Xuyên','Lập Thạch','Tam Đảo'];
        }
        
        // ... Add more logic here
        
        return response()->json($districts);
    }
}
