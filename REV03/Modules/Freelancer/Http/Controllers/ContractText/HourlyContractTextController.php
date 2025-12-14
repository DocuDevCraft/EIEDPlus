<?php

namespace Modules\Freelancer\Http\Controllers\ContractText;

use Illuminate\Routing\Controller;
use Morilog\Jalali\Jalalian;

class HourlyContractTextController extends Controller
{
    /*
    * Get Data
    * Route: /api/my-account/accept-rules
    * GET
    * */
    static public function ContractText($Freelancer, $price)
    {
        $Date = Jalalian::now()->format('Y/m/d');
        $DateNextYear = Jalalian::now()->addYears(1)->format('Y/m/d');
        $Freelancer->mahale_sodoor = $Freelancer->mahale_sodoor ? 'صادره از ' . $Freelancer->mahale_sodoor : '';
        $Freelancer->address = $Freelancer->address ? 'به نشانی ' . $Freelancer->address : '';
        $Freelancer->postal_code = $Freelancer->postal_code ? 'کدپستی ' . $Freelancer->postal_code : '';
        $priceRial = number_format($price . 0);
        $Contract = <<<HTML
        <div dir='rtl' style='font-size: 10pt; font-size: 15px; line-height: 2.5; font-family: titr'>
            <div style='text-align: center; font-weight: bold; margin-bottom: 105px; font-size: 26px'>قرارداد<br>ارائه خدمات کارشناسی</div>
            <div style='text-align: center; font-weight: bold; margin-bottom: 100px; font-size: 26px;'>شماره قرارداد: <br> <span style="font-family: Roboto; direction: ltr !important;">EIED-CON-GEN-04-SE-453826</span></div>
            <div style='text-align: center; font-weight: bold; margin-bottom: 100px; font-size: 26px; page-break-after: always;'>تاریخ قرارداد: {$Date}</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px; text-align: center'>به نام خدا</div>
            <div>این قرارداد در تاریخ {$Date} در تهران بین شرکت طراحی و مهندسی صنایع انرژی (EIED) به شماره ثبت 140735 و شناسه ملی 10101837663 و دارای کد اقتصادی به شماره 5933-1119-4111 به نمایندگی آقای سید محمدعلی غفاریزاده (مدیرعامل و نایب رئیس هیئت مدیره) و آقای حسین ریحانی اصفهانی (عضو هیئت مدیره) به نشانی تهران - محله اراج - خیابان گلشن - خیابان شهید مصباح (گلزار) - پلاک 27، کدپستی 1693938611 و شماره تماس 72385000 که در این قرارداد "کارفرما" نامیده می شود، از یک طرف و آقای {$Freelancer->users->first_name} {$Freelancer->users->last_name}  به شماره شناسنامه {$Freelancer->shenasnameh} و کد ملی {$Freelancer->meli_code} {$Freelancer->mahale_sodoor} {$Freelancer->address} {$Freelancer->postal_code} و شماره تماس {$Freelancer->users->phone} که در این قرارداد "کارشناس" نامیده می شود از طرف دیگر بر اساس توافقات به عمل آمده و مطابق با شرایط و مشخصات زیر منعقد و مفاد آن برای طرفین لازم الاجرا می باشد. </div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده یک -  موضوع قرارداد</div>
            <div style='font-weight: 600; margin-bottom: 26px; text-align: justify'>موضوع قرارداد عبارتست از ارائه خدمات کارشناسی جهت </div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده دو –  اسناد و مدارک قرارداد</div>
            <div style='font-weight: 600; text-align: justify'>1-2  قرارداد حاضر (و الحاقیه های آتی قرارداد).</div>
            <div style='font-weight: 600; text-align: justify'>2-2  کلیه دستورکارهایی که در حین قرارداد توسط کارفرما ابلاغ می گردد.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده سه –  مدت قرارداد</div>
            <div style='font-weight: 600; text-align: justify'>مدت این قرارداد از تاریخ {$Date} تا تاریخ {$DateNextYear} به مدت ۱۲ ماه شمسی می باشد که در صورت توافق طرفین قابل تمدید خواهد بود.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده چهار -  حق الزحمه کارشناس</div>
            <div style='font-weight: 600; text-align: justify'>1-4 حق الزحمه کارشناس برای انجام خدمات موضوع قرارداد بابت کارکرد ماهیانه به میزان 200 ساعت و به نرخ هر ساعت کارکرد معادل {$priceRial}ريال به صورت ناخالص می باشد.</div>
            <div style='font-weight: 600; text-align: justify'>2-4 حق الزحمه به صورت ماهیانه، براساس کاربرگ زمانی کارکرد در پایان هر ماه و با تایید کارفرما و پس از اعمال کسر مالیات بر درآمد مطابق قوانین و ضوابط جاری کشور قابل پرداخت خواهد بود.</div>
            <div style='font-weight: 600; text-align: justify; margin-bottom: 25px'>3-4 تا پایان مدت قرارداد هیچگونه افزایش قیمت، تعدیل و مابه التفاوت به کارشناس تعلق نخواهد گرفت و بعداً از هیچ بابت حق درخواست اضافه پرداختی ندارد.</div>
            <div style='font-weight: 600; text-align: justify'>اطلاعات حساب بانکی کارشناس جهت پرداخت حق الزحمه به شرح زیر می باشد:</div>
            <div style='font-weight: 600; text-align: justify'>1-  شماره شبا حساب بانکی 			<span style="margin-right: 70px">IR{$Freelancer->shaba}</span></div>
            <div style='font-weight: 600; text-align: justify'>5-  نوع حساب (ارزی - ريالی)			<span style="margin-right: 58px">ریالی</span> </div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده پنج –  تعهدات کارشناس</div>
            <div style='font-weight: 600; text-align: justify'>1-5 کارشناس حق واگذاري تمام یا قسمتی از تعهدات موضوع این قرارداد را به شخص یا اشخاص ثالث ندارد.</div>
            <div style='font-weight: 600; text-align: justify'>2-5 کارشناس متعهد مي گردد گزارش پيشرفت فعاليت ها را در فواصل ماهيانه و به طور مستمر به كارفرما ارائه كند.</div>
            <div style='font-weight: 600; text-align: justify'>3-5 کارشناس متعهد است وظایف محوله مربوط به ارائه خدمات کارشناسی را در محل شرکت کارفرما و یا خارج از آن که مطابق با نظر کارفرما و یا نماینده وی محول می شود را به نحو احسن و در زمان مورد نیاز به انجام رساند</div>
            <div style='font-weight: 600; text-align: justify'>4-5 کارشناس متعهد است که در زمان حضور در دفتر کارفرما مقررات ایمنی، حفاظتی، اداری و HSE کارفرما را دقیقاً رعایت نماید. </div>
            <div style='font-weight: 600; text-align: justify'>5-5 کارشناس مکلف است تا در پایان مدت قرارداد (در صورت عدم تمدید) یا در صورت خاتمه قرارداد به هر علت و در هر زمان، نسبت به تحویل اسناد و مدارک و انتقال امور و پرونده های در دست اقدام به نماینده کارفرما در اسرع وقت اقدام نماید. </div>
            <div style='font-weight: 600; text-align: justify'>6-5 کارشناس موظف است بر حسب ضرورت و اعلام کارفرما در جلسات فنی با کارفرما شرکت نموده و از مدارک تهیه شده دفاع نماید.</div>
            <div style='font-weight: 600; text-align: justify'>7-5 کارشناس متعهد می گردد که کلیه فایلهای محاسباتی پروژه برای ارجاع احتمالی در آینده را به طور مستمر و همزمان با ارائه گزارش ها و مدارک تهیه شده، در اختیار کارفرما قرار دهد. </div>
            <div style='font-weight: 600; text-align: justify'>8-5 کارشناس می بایست کلیه قراردادهای کارشناسی، عضویت در هیئت مدیره شرکتها اعم از موظف یا غیر موظف و سهامداری بالای 5 درصد در شرکتهای دولتی، خصوصی و امثالهم را در ابتدا و طول مدت قرارداد خود افشاء نمایند. </div>
            <div style='font-weight: 600; text-align: justify'>9-5 کارشناس می بایست کلیه تغییرات اطلاعات در خصوص بند فوق را پیش از ابلاغ قرارداد و در حین اجرای موضوع قرارداد به کارفرما اعلام نماید و چنانچه در اطلاعات ارائه شده تغییری حاصل گردد، کارفرما مجاز می باشد تا عدم وجود تعارض منافع ( شامل و نه محدود به متغیرهایی نظیر فعالیت کارشناس در صنعت مشابه، حضور و ارتباط با شرکتهای رقیب و...) را مجددا بررسی و در صورت احراز وجود تعارض، نسبت به فسخ قرارداد فی مابین اقدام نموده و کارشناس حق هرگونه اعتراض را از خود سلب و ساقط می نماید.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده شش –  سایر شرایط </div>
            <div style='font-weight: 600; text-align: justify'>1-6 این قرارداد هیچگونه تعهدی برای استخدام کارشناس از طرف کارفرما ایجاد ننموده و تعهد دیگری جز آنچه در متن این قرارداد آمده است برای کارفرما وجود ندارد.</div>
            <div style='font-weight: 600; text-align: justify'>2-6 کارفرما از لحاظ خدمات درمانی هیچگونه تعهدی در قبال کارشناس ندارد و صرفاً نسبت به پرداخت حق الزحمه کارشناس براساس کارکرد اقدام می نماید.</div>
            <div style='font-weight: 600; text-align: justify'>3-6 تهیه مکان مناسب و تجهیزات مورد نیاز (رایانه و تجهیزات جانبی، ملزومات اداری و مصرفی) و نیز تامین غذای کارشناس در زمان حضور در دفتر کارفرما بر عهده کارفرما می باشد.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده هفت –  نماینده کارفرما</div>
            <div style='font-weight: 600; text-align: justify'>نماینده کارفرما در این قرارداد مدیر بخش توسعه تکنولوژی می باشد.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده هشت - محرمانگی اطلاعات </div>
            <div style='font-weight: 600; text-align: justify'>1-8 کلیه اطلاعات، اسناد، دانش فنی، اطلاعات مالی، محاسباتی و اطلاعات مربوط به کارفرما اعم از کتبی و الکترونیک، که به سبب قرارداد حاضر افشاء می‌گردد و یا به اقتضاء و ضرورت جهت انجام تعهدات در اختیار کارشناس قرار می گیرد و یا ضمن کار به آنها دسترسی پیدا می کند، در هر قالبی که باشد، اعم از اینکه قید محرمانه داشته و یا نداشته باشد، محرمانه محسوب شده و کارشناس ملزم به عدم افشای آن و قراردادهای مرتبط با آن و همچنین سایر اطلاعات مرتبط در طول مدت قرارداد و پس از خاتمه آن به هر دلیلی می باشد و نباید این اطلاعات را در مواردی غیر از هدف اجرای قرارداد استفاده کرده یا به اشخاص ثالث ارائه و یا افشاء نماید و موظف است پس از انجام موضوع قرارداد، تمامی مدارک را به کارفرما مسترد نماید. </div>
            <div style='font-weight: 600; text-align: justify'>2-8 عدم افشاء و حفظ اطلاعات و اسناد محرمانه صرفاً شامل عدم ارائه اسناد و مدارک بصورت کپی و یا از طریق ارسال الکترونیک نخواهد بود و هرگونه برقراری رابطه کاری، شرکت در جلسه، بحث و مذاکره خارج از چارچوب که به نحوی منجر به انتقال اطلاعات و یا بخشی از آن به اشخاص غیرمسئول و یا سایر اشخاص باشد را نیز دربر می‌گیرد. </div>
            <div style='font-weight: 600; text-align: justify'>3-8 کارشناس متعهد می گردد در صورت فسخ یا پایان قرارداد، کلیه اطلاعات، مستندات و خروجي هاي پروژه را کماکان محرمانه تلقی نماید و در صورت همکاری با سایر کارفرما ها، شرکت ها و سازندگان از افشاء اطلاعات به آنها و به هر طریقی کماکان خودداری نماید. </div>
            <div style='font-weight: 600; text-align: justify'>4-8 کلیه موارد مرقوم در بند های فوق حکم امانت را داشته، لذا در صورت افشای آن موضوع از طریق محاکم قضایی و قابل پیگرد می باشد.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده نه –  قانون حاکم</div>
            <div style='font-weight: 600; text-align: justify'>این قرارداد از هر حیث تابع قوانین و مقررات جمهوری اسلامی ایران می باشد.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده ده –  حل اختلاف </div>
            <div style='font-weight: 600; text-align: justify'>1-10 در صورت بروز هرگونه اختلاف فیمابین طرفین ناشی از و یا مرتبط با این قرارداد، موضوع اختلاف ابتداء از طریق مذاکره با حضور نمایندگان طرفین بررسی و حل و فصل خواهد شد و در صورت عدم حصول نتیجه مطلوب ظرف مدت دو ماه از تاریخ بروز اختلاف و درخواست یکی از طرفین، موضوع اختلاف به هیأت کارشناسی سه نفره (مشتمل بر یک کارشناس از طرف کارفرما، یک کارشناس از جانب کارشناس و کارشناس سوم به انتخاب کارفرما از کانون کارشناسان رسمی دادگستری) ارجاع خواهد شد. هزینه ارجاع امر به کارشناس رسمی دادگستری، بالمناصفه توسط طرفین پرداخت خواهد گردید.</div>
            <div style='font-weight: 600; text-align: justify'>2-10 هیأت مزبور ظرف مهلت حداکثر دو ماه از تاریخ تشکیل نسبت به صدور نظریه کارشناسی اقدام خواهد نمود. در صورت عدم حل و فصل موضوع ظرف مهلت 3 ماه از تاریخ درخواست یکی از طرفین یا 2 ماه از تاریخ تشکیل هیأت کارشناسی و یا در صورت عدم پذیرش نظریه کارشناسی توسط کارفرما، موضوع اختلاف از طریق مراجعه به مراجع قضائی استان تهران حل و فصل خواهد شد.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده یازده –  موارد فورس ماژور</div>
            <div style='font-weight: 600; text-align: justify; margin-bottom: 15px'>اگر به عللی خارج از حیطه اختیار و اراده طرفین انجام تمام یا قسمتی از تعهدات موضوع این قرارداد امکان پذیر نباشد، مادامی که جهات مزبور ادامه دارد، عدم انجام تعهداتی که متأثر از عوامل غیرمترقبه باشد، تخلف از قرارداد محسوب نمی‌ شود. </div>
            <div style='font-weight: 600; text-align: justify'>1-11 نظر کارفرما در خصوص موارد مشمول فورس ماژور ملاک عمل خواهد بود. </div>
            <div style='font-weight: 600; text-align: justify'>2-11 هيچيك از طرفين قرارداد در مورد اين ماده نميتوانند ادعايي منجمله دريافت خسارت به دليل توقف، خاتمه و يا تأخير اجراي تعهدات بر عليه ديگري داشته باشند. هيچگونه تعديلي ناشي از حادثه قهريه به قرارداد تعلق نمي گيرد. </div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده دوازده –  فسخ قرارداد</div>
            <div style='font-weight: 600; text-align: justify'>1-12 كارفرما مي‌تواند در صورت تحقق هريک از موارد زير، قرارداد را به صوت جزئی و یا کلی فسخ نمايد و خسارات وارده را از محل مطالبات کارشناس بدون نیاز به تشریفات قضایی كسر نمايد.</div>
            <div style='font-weight: 600; text-align: justify; padding-right: 25px'>1-1-12 تأخیر در شروع به کار بیش از یک هفته از تاریخ ابلاغ قرارداد.</div>
            <div style='font-weight: 600; text-align: justify; padding-right: 25px'>2-1-12 تأخير در انجام تعهدات توسط کارشناس. </div>
            <div style='font-weight: 600; text-align: justify; padding-right: 25px'>3-1-12 در صورت قصور و مسامحه در اجراي قرارداد و در نتيجه احراز عدم توانايي کارشناس جهت ايفاي تعهدات مندرج در قرارداد بنا به تشخيص کارفرما. </div>
            <div style='font-weight: 600; text-align: justify; padding-right: 25px'>4-1-12 انتقال يا واگذاري تمام يا بخشي از موضوع قرارداد به غير بدون مجوز کتبي کارفرما. (بند 5-1)</div>
            <div style='font-weight: 600; text-align: justify; padding-right: 25px'>5-1-12 وجود واسطه و ارتشاء و يا تسري و شمول هرگونه ممنوعيت قانوني به کارشناس. </div>
            <div style='font-weight: 600; text-align: justify;'>2-12 نظر کارفرما در خصوص قصور یا عدم قصور کارشناس در انجام موضوع قرارداد، ملاک عمل خواهد بود و کارشناس حق اعتراض نخواهد داشت. </div>
            <div style='font-weight: 600; text-align: justify;'>3-12 کارفرما اختيار دارد ضمن فسخ قرارداد ناشي از بندهاي فوق نسبت به جبران کلیه خسارت وارده به اضافه 10% (ده درصد) از مطالبات کارشناس اقدام نمايد و کارشناس حق اعتراض نخواهد داشت.</div>
            <div style='font-weight: 600; text-align: justify;'>4-12 زمان فسخ قرارداد، تاریخ ارسال نامه فسخ به ایمیل کارشناس یا ارسال نامه از طریق پست پیشتاز به آدرس رسمی کارشناس مندرج در صدر قرارداد خواهد بود. </div>
            <div style='font-weight: 600; text-align: justify;'>5-12 نظر کارفرما در خصوص میزان خسارات وارده، ملاک عمل خواهد بود و کارشناس حق اعتراض نخواهد داشت.</div>
            <div style='font-weight: 600; text-align: justify;'>6-12 در صورت فسخ قرارداد توسط کارفرما، کارشناس موظف است ظرف هفت روز کاری از تاریخ فسخ قرارداد، نسبت به ارائه صورت وضعیت کلیه فعالیت های انجام شده (مطابق شرح کار قرارداد) اقدام نماید و کارفرما ظرف 14 روزکاری از تاریخ دریافت نسخه اصل صورت وضعیت، نسبت به بررسی و اعلام نظر اقدام خواهد نمود. نظر کارفرما در خصوص صورت وضعیت و میزان کارهای صورت گرفته قطعی و ملاک عمل خواهد بود. </div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده سیزده –  خاتمه قرارداد </div>
            <div style='font-weight: 600; text-align: justify;'>کارفرما می تواند در هر زمان و بنا به صلاحدید خود نسبت به اعلام خاتمه قرارداد اقدام نموده و در صورتیکه اعلام خاتمه قرارداد ناشی از قصور کارشناس نباشد، کارفرما نسبت به محاسبه و پرداخت حق الزحمه کارشناس تا تاریخ اعلام خاتمه قرارداد اقدام می نماید.</div>
            <div style='font-weight: 600; text-align: justify;'>1-13 زمان خاتمه قرارداد، تاریخ ارسال نامه خاتمه قرارداد به ایمیل کارشناس یا ارسال نامه از طریق پست پیشتاز به آدرس رسمی کارشناس مندرج در صدر قرارداد حاضر خواهد بود.</div>
            <div style='font-weight: 600; text-align: justify;'>2-13 در صورت خاتمه قرارداد توسط کارفرما، کارشناس موظف است ظرف هفت روز کاری از تاریخ خاتمه قرارداد، نسبت به ارائه صورت وضعیت کلیه فعالیت های انجام شده (مطابق شرح کار قرارداد) اقدام نماید و کارفرما ظرف 14 روز کاری از تاریخ دریافت نسخه اصل صورت وضعیت، نسبت به بررسی و اعلام نظر اقدام خواهد نمود. نظر کارفرما در خصوص صورت وضعیت و میزان کارهای صورت گرفته قطعی و ملاک عمل خواهد بود.</div>

            <div style='margin-top: 40px; font-weight: bold; font-size: 17px;'>ماده چهارده -  نسخ قرارداد </div>
            <div style='font-weight: 600; text-align: justify;'>این قرارداد در چهارده ماده و 2 نسخه تهیه شده که هر نسخه حکم واحد داشته و یک نسخه به کارشناس ابلاغ شده است. </div>


           <table style='width: 100%; text-align: center; margin-top: 120px; margin-bottom: 50px'>
                <tbody>
                    <tr>
                        <td style="width: 50%;vertical-align: top">
                            <div style='font-weight: bold; font-size: 17px;'>کارفرما</div>
                            <div style='margin-top: 40px; font-weight: bold; font-size: 17px; margin-bottom: 10px'>شرکت طراحی و مهندسی صنایع انرژی</div>
                            <span style='font-weight: bold; font-size: 17px; text-align: justify; display: inline-block'>نام و نام خانوادگی: سید محمدعلی غفاریزاده<br>سمت:  مدیرعامل و نایب رئیس هیئت مدیره<br>امضاء</span>
                            <br><br><br>
                            <div style='font-weight: bold; font-size: 17px;'>نام و نام خانوادگی: حسین ریحانی اصفهانی</div>
                            <div style='font-weight: bold; font-size: 17px;'>سمت: عضو هیئت مدیره</div>
                            <div style='font-weight: bold; font-size: 17px;'>امضاء و مهر:</div>
                        </td>
                        <td style="width: 50%;vertical-align: top">
                            <div style='margin-bottom: 70px;font-weight: bold; font-size: 17px;'>کارشناس</div>
                            <span style='font-weight: bold; font-size: 17px; text-align: justify; display: inline-block'>نام و نام خانوادگی: {$Freelancer->users->first_name} {$Freelancer->users->last_name}<br>امضاء و اثر انگشت:</span>
                        </td>
                    </tr>
                </tbody>
           </table>
        </div>
        HTML;

        return $Contract;
    }
}
