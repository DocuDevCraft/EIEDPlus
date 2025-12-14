<?php

namespace Modules\FreelancerOffer\Http\Controllers\OfferContract\ContractText;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\Freelancer;

class OfferContractTextController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/accept-rules
    * GET
    * */
    static public function ContractText($WorkPackage, $Freelancer, $price, $time)
    {
        $Contract = <<<HTML
        <div dir='rtl' style='font-size: 10pt; font-size: 15px; line-height: 2.5;'>
            <div style='text-align: center; font-weight: bold; margin-bottom: 25px; font-size: 20px'>برگ پیشنهاد قیمت</div>
            <div style='text-align: center; font-weight: 600; margin-bottom: 50px; font-size: 20px'>قرارداد <span style='font-weight: bold;'>{$WorkPackage->title}</span> به شماره <span style='font-weight: bold;'>{$WorkPackage->unique_id}</span></div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px'>به: شركت طراحی و مهندسی صنایع انرژی</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px'>اینجانب {$Freelancer->users->first_name} {$Freelancer->users->last_name} به کد ملی {$Freelancer->meli_code} پس از بررسي و آگاهي كامل و پذيرش تعهدات و مسئوليت در مورد مطالب و مندرجات دستورالعمل شرکت در مناقصه ، متن قرارداد، شرح كار و با اشراف كامل بر پروژه و بطور كلي تمامي مدارك و اسناد مناقصه و با اطلاع كامل از جميع شرايط و عوامل موجود از لحاظ انجام خدمات مورد مناقصه؛ حاضر هستم:</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>1-  كليه خدمات مشروح در مدارك این مناقصه را طبق شرايط و مشخصات مندرج در آنها به بهترين وجه و بطوريكه از هر حيث مورد تایید شركت طراحی و مهندسی صنایع انرژی واقع گردد، برای انجام موضوع قرارداد {$WorkPackage->title} به مبلغ كل:</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>به عدد: {$price["number"]} (به حروف: {$price["numberString"]}) ريال به صورت خالص، و در $time روز  انجام دهم.</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>2-  چنانچه پيشنهاد اينجانب مورد قبول قرار گيرد و بعنوان برنده مناقصه انتخاب شوم تعهد مي نمايم:</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>الف ) بلافاصله پس از اعلام شركت طراحی و مهندسی صنایع انرژی مبني بر شروع كار، خدمات موضوع مناقصه را شروع نمایم. مادامي كه قرارداد رسمي به امضاء نرسيده است، پيشنهاد حاضر و اعلام قبولي آن در حكم قرارداد تلقي مي گردد.</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>ج ) اينجانب تایيد مي نمايم كه كليه ضمائم، اسناد و مدارك مناقصه جزء لاينفك اين پيشنهاد محسوب مي شود.</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>3- اينجانب اطلاع كامل دارم كه دستگاه مناقصه گزار الزامي براي واگذاري کل و یا بخشی از كار به هر يك از پيشنهاد دهندگان و يا حائز حداقل قيمت پيشنهادي را ندارد.</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 15px;'>4- مدت اعتبار پيشنهاد:</div>
            <div style='margin-top: 40px; font-weight: bold; margin-bottom: 55px;'>پيشنهاد حاضر از تاريخ آخرين مهلت قبول پيشنهادات تا مدت 90 (نود) روز معتبر و غير قابل استرداد بوده و در عرض اين مدت هر زمان كارفرما قبولي خود را نسبت به آن اعلام نمايد، طبق ردیف 2 فوق براي طرفين الزام آور خواهد بود و در صورتيكه اينجانب حاضر به عقد قرارداد و يا ارائه خدمات نشوم يا از شرايط مقرر در اسناد مناقصه عدول نمايم، شركت طراحی و مهندسی صنایع انرژی حق دارد نسبت به اعمال جرایم مطابق با موارد مندرج در اسناد مناقصه اقدام نماید.</div>

            <div style='text-align: center; font-weight: bold; margin-bottom: 17px; font-size: 20px'>«فرم تعهدنامه عدم تعارض منافع»</div>
            <div style='font-weight: 600; margin-bottom: 26px; text-align: justify'>اینجانب {$Freelancer->users->first_name} {$Freelancer->users->last_name} و کد ملی {$Freelancer->meli_code} ، به موجب این تعهدنامه در کمال صحت و سلامت اقرار مینمایم که در موضوع و ارجاع با عنوان قرارداد بسته های کاری شرکت طراحی و مهندسی صنایع انرژی (EIED) هیچ گونه تعارض منافع با افراد یا موسسات دیگر ندارد؛ چنانچه در حین اجرای کار هرگونه ادعایی در مورد مالکیت مادی/معنوی و یا همکاری مشابه در سایر شرکتها برای آن مطرح شود، کلیه مسئولیتهای مرتبط بر عهده اینجانبان بوده و تا زمان شفاف شدن کامل موضوع، کلیه فرآیندهای اجرایی متوقف و ادامه روند منوط به اخذ مجوز از کارفرما میباشد.</div>
            <div style='font-weight: 600; margin-bottom: 26px; text-align: justify'>ضمنا درصورت عدم رعایت موارد فوق مسئولیت ورود خسارت به کارفرما و اشخاص ثالث به عهده اینجانب میباشد و کارفرما در این خصوص هیچگونه مسئولیتی نخواهد داشت.</div>

        </div>
        HTML;

        return $Contract;
    }
}
