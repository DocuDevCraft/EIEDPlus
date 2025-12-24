<?php

namespace App\Http\Controllers;

use App\Models\User;
use Modules\EmploymentAdvertisement\Entities\EmploymentAdvertisementCategory;
use Modules\EmploymentAdvertisement\Entities\EmploymentAdvertisementProficiency;
use Modules\FileLibrary\Entities\FileLibrary;
use Modules\ResumeIntroducer\Entities\ResumeIntroducer;
use Modules\ResumeManager\Entities\ResumeConfirmReject;
use Modules\ResumeManager\Entities\ResumeManager;
use Modules\Users\Entities\Users;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the Index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('site.pages.index');
    }

    /* Short text */
    public static function TruncateString($str, $chars, $to_space, $replacement = "...")
    {
        if ($chars > strlen($str)) return $str;

        $str = substr($str, 0, $chars);
        $space_pos = strrpos($str, " ");
        if ($to_space && $space_pos >= 0)
            $str = substr($str, 0, strrpos($str, " "));

        return ($str . $replacement);
    }

    /* Text Censor */
    public static function Censor($string)
    {
        $replacement = str_repeat('*', rand(6, 9));
        return substr_replace($string, $replacement, 4, -4);
    }

    /* Convert Gender */
    public static function ConvertGender($gender)
    {
        $CallBack = 0;
        switch ($gender) {
            case 'male' :
                $CallBack = 'آقا';
                break;
            case 'female' :
                $CallBack = 'خانم';
                break;
        }

        return $CallBack;
    }

    /* Convert Support Status */
    public static function ConvertSupportStatus($status)
    {
        $CallBack = 0;
        switch ($status) {
            case 'new' :
                $CallBack = 'در انتظار پاسخ';
                break;
            case 'replied' :
                $CallBack = 'پاسخ داده شده';
                break;
            case 'closed' :
                $CallBack = 'موضوع بسته شده';
                break;
            case 'pending' :
                $CallBack = 'در انتظار پاسخ';
                break;
        }

        return $CallBack;
    }

    /* Convert Support Priority */
    public static function ConvertSupportPriority($priority)
    {
        $CallBack = 0;
        switch ($priority) {
            case 'high' :
                $CallBack = 'زیاد';
                break;
            case 'medium' :
                $CallBack = 'متوسط';
                break;
            case 'low' :
                $CallBack = 'کم';
                break;
        }

        return $CallBack;
    }

    /* State Code */
    public static function selectState($type = 'name', $state)
    {
        $CallBack = 0;
        if ($type == 'name') {
            switch ($state) {
                case 'تهران' :
                    $CallBack = 1;
                    break;
                case 'گیلان' :
                    $CallBack = 2;
                    break;
                case 'آذربایجان شرقی' :
                    $CallBack = 3;
                    break;
                case 'خوزستان' :
                    $CallBack = 4;
                    break;
                case 'فارس' :
                    $CallBack = 5;
                    break;
                case 'اصفهان' :
                    $CallBack = 6;
                    break;
                case 'خراسان رضوی' :
                    $CallBack = 7;
                    break;
                case 'قزوین' :
                    $CallBack = 8;
                    break;
                case 'سمنان' :
                    $CallBack = 9;
                    break;
                case 'قم' :
                    $CallBack = 10;
                    break;
                case 'مرکزی' :
                    $CallBack = 11;
                    break;
                case 'زنجان' :
                    $CallBack = 12;
                    break;
                case 'مازندران' :
                    $CallBack = 13;
                    break;
                case 'گلستان' :
                    $CallBack = 14;
                    break;
                case 'اردبیل' :
                    $CallBack = 15;
                    break;
                case 'آذربایجان غربی' :
                    $CallBack = 16;
                    break;
                case 'همدان' :
                    $CallBack = 17;
                    break;
                case 'کردستان' :
                    $CallBack = 18;
                    break;
                case 'کرمانشاه' :
                    $CallBack = 19;
                    break;
                case 'لرستان' :
                    $CallBack = 20;
                    break;
                case 'بوشهر' :
                    $CallBack = 21;
                    break;
                case 'کرمان' :
                    $CallBack = 22;
                    break;
                case 'هرمزگان' :
                    $CallBack = 23;
                    break;
                case 'چهارمحال و بختیاری' :
                    $CallBack = 24;
                    break;
                case 'یزد' :
                    $CallBack = 25;
                    break;
                case 'سیستان و بلوچستان' :
                    $CallBack = 26;
                    break;
                case 'ایلام' :
                    $CallBack = 27;
                    break;
                case 'کهگلویه و بویراحمد' :
                    $CallBack = 28;
                    break;
                case 'خراسان شمالی' :
                    $CallBack = 29;
                    break;
                case 'خراسان جنوبی' :
                    $CallBack = 30;
                    break;
                case 'البرز' :
                    $CallBack = 31;
                    break;
            }
        } elseif ($type == 'number') {
            switch ($state) {
                case 1 :
                    $CallBack = 'تهران';
                    break;
                case 2 :
                    $CallBack = 'گیلان';
                    break;
                case 3 :
                    $CallBack = 'آذربایجان شرقی';
                    break;
                case 4 :
                    $CallBack = 'خوزستان';
                    break;
                case 5 :
                    $CallBack = 'فارس';
                    break;
                case 6 :
                    $CallBack = 'اصفهان';
                    break;
                case 7 :
                    $CallBack = 'خراسان رضوی';
                    break;
                case 8 :
                    $CallBack = 'قزوین';
                    break;
                case 9 :
                    $CallBack = 'سمنان';
                    break;
                case 10 :
                    $CallBack = 'قم';
                    break;
                case 11 :
                    $CallBack = 'مرکزی';
                    break;
                case 12 :
                    $CallBack = 'زنجان';
                    break;
                case 13 :
                    $CallBack = 'مازندران';
                    break;
                case 14 :
                    $CallBack = 'گلستان';
                    break;
                case 15 :
                    $CallBack = 'اردبیل';
                    break;
                case 16 :
                    $CallBack = 'آذربایجان غربی';
                    break;
                case 17 :
                    $CallBack = 'همدان';
                    break;
                case 18 :
                    $CallBack = 'کردستان';
                    break;
                case 19 :
                    $CallBack = 'کرمانشاه';
                    break;
                case 20 :
                    $CallBack = 'لرستان';
                    break;
                case 21 :
                    $CallBack = 'بوشهر';
                    break;
                case 22 :
                    $CallBack = 'کرمان';
                    break;
                case 23 :
                    $CallBack = 'هرمزگان';
                    break;
                case 24 :
                    $CallBack = 'چهارمحال و بختیاری';
                    break;
                case 25 :
                    $CallBack = 'یزد';
                    break;
                case 26 :
                    $CallBack = 'سیستان و بلوچستان';
                    break;
                case 27 :
                    $CallBack = 'ایلام';
                    break;
                case 28 :
                    $CallBack = 'کهگلویه و بویراحمد';
                    break;
                case 29 :
                    $CallBack = 'خراسان شمالی';
                    break;
                case 30 :
                    $CallBack = 'خراسان جنوبی';
                    break;
                case 31 :
                    $CallBack = 'البرز';
                    break;
            }
        }

        return $CallBack;
    }

    /* Get User Full Name */
    public static function GetUserData($id, $data = 'name')
    {
        $User = Users::find($id);

        $CallBack = '';

        switch ($data) {
            case 'name':
                $CallBack = $User->first_name . ' ' . $User->last_name;
                break;
            case 'email':
                $CallBack = $User->email;
                break;
            case 'avatar':
                $CallBack = $User->avatar;
                break;
            case 'role':
                $CallBack = $User->role;
                break;
        }

        return $CallBack;
    }

    /* Get Avatar */
    public static function GetAvatar($size, $retineSize, $id = '')
    {
        if ($id) {
            $UserAvatar = FileLibrary::find($id);

            if ($UserAvatar->extension != 'svg') {
                $Avatar['path'] = url('storage/' . preg_replace("#^app/public#", "", $UserAvatar->path) . $size . '/' . $UserAvatar->file_name);
                $Avatar['pathX2'] = url('storage/' . preg_replace("#^app/public#", "", $UserAvatar->path) . $retineSize . '/' . $UserAvatar->file_name);
            } else {
                $Avatar['path'] = url('storage/' . preg_replace("#^app/public#", "", $UserAvatar->path) . 'full/' . $UserAvatar->file_name);
            }
        } else {
            $Avatar['path'] = '';
            $Avatar['pathX2'] = '';
        }

        return $Avatar;
    }

    /* Get File Name */
    public static function GetFileName($id = '')
    {
        $File = FileLibrary::find($id);

        return $File->org_name;
    }

    /* Get File Path */
    public static function GetFilePath($id = '')
    {
        $File = FileLibrary::find($id);

        return url('storage/' . preg_replace("#^app/public#", "", $File->path) . $File->file_name);
    }

    /* Avatar Generation */
    public static function AvatarGenerate($gender)
    {
        $Avatar = url('storage/avatars/' . $gender . '/' . rand(1, 3) . '.jpg');

        return $Avatar;
    }

    public static function EncryptDecrypt($String, $SecretKey, $type = 'encrypt')
    {
        $Callback = '';

        function encrypt($string, $key)
        {
            $result = '';
            for ($i = 0; $i < strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                $char = chr(ord($char) + ord($keychar));
                $result .= $char;
            }

            return base64_encode(base64_encode(base64_encode(base64_encode($result))));
        }

        function decrypt($string, $key)
        {
            $result = '';
            $string = base64_decode(base64_decode(base64_decode(base64_decode($string))));

            for ($i = 0; $i < strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key)) - 1, 1);
                $char = chr(ord($char) - ord($keychar));
                $result .= $char;
            }

            return $result;
        }

        switch ($type) {
            case 'encrypt' :
                $Callback = encrypt($String, $SecretKey);
                break;
            case 'decrypt' :
                $Callback = decrypt($String, $SecretKey);
                break;
        }

        return $Callback;
    }

    /* Role Translate */
    public static function RoleTranslation($role)
    {
        $Callback = '';
        switch ($role) {
            case 'admin':
                $Callback = 'مدیر ارشد';
                break;
            case 'engineeringManager':
                $Callback = 'مدیر امور مهندسی';
                break;
            case 'sectionManager':
                $Callback = 'مدیر بخش';
                break;
            case 'workPackageManager':
                $Callback = 'مسئول بسته کاری';
                break;
            case 'chiefAppraiser':
                $Callback = 'سرارزیاب';
                break;
            case 'appraiser':
                $Callback = 'ارزیاب';
                break;
            case 'operator':
                $Callback = 'اپراتور';
                break;
            case 'support':
                $Callback = 'پشتیبانی';
                break;
            case 'author':
                $Callback = 'نویسنده';
                break;
            case 'user' || 'freelancer':
                $Callback = 'فریلنسر';
                break;
            default :
                $Callback = 'کاربر ناشناس';
                break;
        }

        return $Callback;
    }

    /* Freelancer Hourly Contract Convert Status */
    public static function FreelancerHourlyContractConvertStatus($status)
    {
        switch ($status) {
            case 'pending';
                return ['در حال بررسی', '#ff8d00'];
            case 'no';
                return ['ندارد', '#FF3838'];
            case 'yes';
                return ['تایید', '#24b300'];
        }
    }

    /* Freelancer Grade CHat Convert Status */
    public static function WorkPackageConvertScale($scale)
    {
        switch ($scale) {
            case 'minor';
                return ['جزئی', ''];
            case 'normal';
                return ['متوسط', ''];
            case 'major';
                return ['بزرگ', ''];
            default:
                return ['-', ''];
        }
    }

    /* Freelancer Grade CHat Convert Status */
    public static function FreelancerGradeChatConvertStatus($status)
    {
        switch ($status) {
            case 'new';
                return ['جدید', '#ff8d00'];
            case 'viewed';
                return ['مشاهده شد', '#24b300'];
        }
    }

    /* Work Package Type Translator */
    public static function WorkPackageTypeConvertStatus($status)
    {
        switch ($status) {
            case 'hourly_contract';
                return ['نفر/ساعت', '#2b98f5'];
            default:
                return ['عمومی', '#e75ec3'];
        }
    }

    /* Offer Convert Status */
    public static function OfferConvertStatus($status)
    {
        switch ($status) {
            case 'pending';
                return ['در حال بررسی', '#FFB302'];
            case 'rejected';
                return ['رد شد', '#FF3838'];
            case 'awaiting_signature';
                return ['در انتظار امضا', '#2DCCFF'];
            case 'winner';
                return ['برنده شد', '#56F000'];
        }
    }

    /* Convert Task Status */
    public static function ConvertTaskStatus($status)
    {
        switch ($status) {
            case '';
                return ['بدون وضعیت', '#FFB302'];
            case 'stop';
                return ['متوقف شد', '#FF3838'];
            case 'completed';
                return ['تکمیل شد', '#56F000'];
        }
    }

    /* Convert Work Package Status */
    public static function ConvertWorkPackageStatus($status)
    {
        return match ($status) {
            'pending' => ['در انتظار بررسی', '#2DCCFF'],
            'pre_accept' => ['در انتظار تایید نهایی', '#2DCCFF'],
            'new' => ['جدید', '#FF3838'],
            'awaiting_signature' => ['در انتظار امضا فریلنسر', '#ae2dff'],
            'pre_activate' => ['در انتظار امضا کارفرما', '#2d81ff'],
            'activated' => ['فعال', '#17b60a'],
            'expired' => ['موعد امضا گذشته است', '#f00'],
            'completed' => ['انجام شده', '#000'],
            default => ['وضعیت ترجمه نشده', '#555'],
        };
    }

    /* Convert Work Package Offer List Status */
    public static function ConvertWorkPackageOfferListStatus($status)
    {
        return match ($status) {
            null => ['در انتظار تایید مدیر بخش', '#2DCCFF'],
            'manager_suggest' => ['در انتظار تایید مدیر ارشد', '#2DCCFF'],
            'accepted' => ['در انتظار ثبت نهایی', '#FF3838'],
            'final' => ['قرارداد ارسال گردید', '#17b60a'],
            default => ['وضعیت ترجمه نشده'],
        };
    }

    /* Convert Work Package Status */
    public static function ConvertPaymentStatus($status)
    {
        return match ($status) {
            'pending' => ['در انتظار وضعیت', '#2DCCFF'],
            'stop' => ['متوقف شد', '#f00'],
            'requested' => ['درخواست واریز', '#56F000'],
            'paid' => ['پرداخت شد', '#56F000'],
            default => ['وضعیت ترجمه نشده'],
        };
    }

    /* Convert Progress Status */
    public static function ConvertProgressStatus($status)
    {
        switch ($status) {
            case 'new';
                return ['بدون وضعیت', '#FFB302'];
            case 'rejected';
                return ['تایید نشد', '#FF3838'];
            case 'completed';
                return ['تایید شد', '#56F000'];
        }
    }

    /* Calculate Progress Percentage */
    public static function CalculateProgressPercentage($id, $type)
    {
        $Progress = 0;
        $Total = 0;

        switch ($type) {
            case 'activity':
                $Category = WorkPackageCategory::where('activity_id', $id)->get();
                if (count($Category)) {
                    foreach ($Category as $itemCat) {
                        $Total += WorkPackageTask::where('category_id', $itemCat->id)->count();
                        $Progress += WorkPackageTask::where('category_id', $itemCat->id)->where('status', 'completed')->count();
                    }
                }
            case 'category':
                $Total += WorkPackageTask::where('category_id', $id)->count();
                $Progress += WorkPackageTask::where('category_id', $id)->where('status', 'completed')->count();
        }


        if ($Progress) {
            return round(($Progress / $Total) * 100);
        } else {
            return 0;
        }
    }
}
