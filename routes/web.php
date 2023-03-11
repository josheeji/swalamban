<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Helper\Helper;
use Illuminate\Support\Facades\Schema;

Auth::routes();

if (Schema::hasTable('site_settings')) {
    $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
    session()->put('site_settings', $settings);
    $locale = App::getLocale();
}

if (Schema::hasTable('site_settings') && !(session()->has('locale_id'))) {
    $language = \App\Models\SiteSetting::where('key', 'preferred_language')->value('value');

    session()->put('locale_id', $language);
    $locale_code = Helper::getLanguageFromId($language);
    session()->put('locale_code', $locale_code);
    app()->setLocale($locale_code);
}

Route::get('locale/{locale}', function ($locale) {
    session()->put('locale_code', $locale);
    session()->put('locale_id', Helper::getIdFromLanguage($locale));
    app()->setLocale($locale);
    return redirect()->back();
});

Route::group(['middleware' => ['localeSetter']], function () {
    Route::group(['prefix' => 'pndcsystem', 'namespace' => 'Admin\Auth', 'middleware' => 'web'], function () {
        Route::get('/', array('as' => 'admin', 'uses' => 'AuthController@redirectLogin'));
        Route::get('login', array('as' => 'admin.login', 'uses' => 'AuthController@getLogin'));
        Route::post('login', array('as' => '', 'before' => 'csrf', 'uses' => 'AuthController@postLogin'));
        Route::get('logout', array('as' => 'admin.logout', 'uses' => 'AuthController@getLogout'));
    });

    Route::post('email-subscription/store', 'EmailSubscriptionController@store')->name('subscription.store');

    Route::get('/', 'HomeController@index')->name('home.index');
    // Route::prefix('page')->group(function () {
    //     Route::get('banner/{id}', 'HomeController@getbanner')->name('page.banner');
    //     Route::post('email/', 'HomeController@postemail')->name('page.email');
    //     Route::get('setting/{name}', 'FrontendController@getsetting')->name('page.setting');
    // });

    // Route::resource('gallery', 'GalleryController');
    Route::get('/gallery', 'GalleryController@index')->name('gallery.index');
    Route::get('/gallery/{slug}', 'GalleryController@show')->name('gallery.show');
    Route::get('/video', ['uses' => 'GalleryController@video', 'as' => 'gallery.video']);

    Route::get('/contact-us', 'ContactController@index')->name('contact.index');
    Route::post('/contact-us', 'ContactController@store')->name('contact.submit');

    // Route::resource('career', 'CareerController');
    Route::get('career', ['uses' => 'CareerController@index', 'as' => 'career.index']);
    Route::get('career/{slug}', ['uses' => 'CareerController@show', 'as' => 'career.show']);
    Route::post('career', ['uses' => 'CareerController@store', 'as' => 'career.store']);

    Route::get('/download', 'DownloadController@index')->name('download.index');
    Route::get('/download/{slug?}', 'DownloadController@show')->name('download.show');

    Route::get('news', 'NewsController@index')->name('news.index');
    Route::get('news/{slug?}', ['uses' => 'NewsController@category', 'as' => 'news.category']);
    Route::get('news/{category?}/{slug?}', 'NewsController@show')->name('news.show');

    // Route::get('syllabus', 'SyllabusController@index')->name('syllabus.index');

    // Route::get('member', 'MemberController@member')->name('member.member');
    // ROute::get('student', 'MemberController@student')->name('member.student');

    // Route::get('members', 'MemberController@index')->name('member.index');
    // ROute::get('member/{id}', 'MemberController@show')->name('member.show');

    //    Route::resource('content', 'ContentController');
    //    Route::get('content/', 'ContentController@index')->name('page.content.index');
    // Route::get('/projects', 'ProjectController@index')->name('project.index');

    // Route::get('content/{slug}/', 'ContentController@show')->name('content.show');
    // Route::get('project/{slug}/', 'ProjectController@show')->name('project.show');

    // Route::get('/faq', 'FaqController@index')->name('faq.index')->where('page', '^(?!.*admin).*$');
    // Route::get('/faq/{slug?}', 'FaqController@category')->name('faq.category')->where('page', '^(?!.*admin).*$');

    // Route::post('/change-language', 'HomeController@changeLanguage')->name('change-language');

    // Route::prefix('blog')->group(function () {
    //     Route::get('/', 'BlogController@index')->name('blog.index');
    //     //        Route::get('{category}', ['uses' => 'BlogController@category', 'as' => 'blog.category']);
    //     Route::get('{slug}', 'BlogController@detail')->name('blog.detail');
    // });
    Route::prefix('stories')->group(function () {
        Route::get('/', 'BlogController@index')->name('stories.index');
        //        Route::get('{category}', ['uses' => 'BlogController@category', 'as' => 'blog.category']);
        Route::get('{slug}', 'BlogController@detail')->name('stories.detail');
    });

    // Route::get('/search', 'FrontendController@Search')->name('search');

    // Route::get('products/feature', ['uses' => 'ProductController@featured', 'as' => 'product.featured']);
    // Route::get('products/compare', ['uses' => 'ProductController@compare', 'as' => 'product.compare']);
    // Route::post('products/enquiry/{slug?}', ['uses' => 'ProductController@enquiry', 'as' => 'product.enquiry']);
    // Route::get('products/{category}', ['uses' => 'ProductController@category', 'as' => 'product.category']);
    Route::get('products', 'ProductController@index')->name('product.index');
    Route::get('products/{slug}', 'ProductController@show')->name('product.show');
    Route::get('products/{category}/{slug}', 'ProductController@show')->name('product.category.show');





    // Route::get('offers', 'OfferController@index')->name('offer.index');
    // Route::get('{page}/offers/{slug?}', 'OfferController@show')->name('offer.show')->where('page', '^(?!.*admin).*$');;
    // Route::get('offers/{slug?}', 'OfferController@show')->name('offer.show');

    // Route::get('services', 'ServiceController@index')->name('services.index');
    // Route::get('services/{slug}', ['uses' => 'ServiceController@show', 'as' => 'services.show']);

    // Route::get('csr-old', 'CsrController@index')->name('csr.index');
    // Route::get('csr-old/{slug}', 'CsrController@show')->name('csr.show');

    // Route::get('atm', 'AtmController@index')->name('atm.index');
    // Route::get('atm/{slug}', 'AtmController@show')->name('atm.show');
    Route::get('branches', 'BranchController@index')->name('branch.index');
    Route::get('central-office', 'BranchController@centralOffice')->name('centralOffice');
    Route::get('area-office', 'BranchController@areaOffice')->name('areaOffice');
    Route::get('information-office', 'BranchController@informationOffice')->name('informationOffice');
    // Route::get('network-points', 'BranchController@index')->name('branch.index');
    // Route::post('network-points', 'BranchController@result')->name('branch.index');
    // Route::post('network-points/search', 'BranchController@search')->name('branch.search');
    // Route::get('network-points/{slug}', 'BranchController@show')->name('branch.show');

    // Route::get('forex', 'ForexController@index')->name('forex.index');
    // Route::get('/trade-finance-treasury/forex', 'ForexController@index');

    // Route::get('emi-calculator', 'EmiController@index')->name('emi.index');

    // Route::get('forex-save', 'ForexController@save')->name('forex.save');

    // Route::get('grievance-handling', 'GrievanceController@create')->name('grievance.create');
    // Route::post('grievance-handling', 'GrievanceController@store')->name('grievance.store');
    // Route::post('departments', 'GrievanceController@departments')->name('grievance.departments');

    // Route::get('calculator', 'CalculatorController@index')->name('calculator.index');

    Route::get('notices', 'NoticeController@pressRelease')->name('press-release');
    Route::get('notices/{slug}', 'NoticeController@show')->name('press-release.show');

    // Route::get('procurement-notice', 'NoticeController@tenderNotice')->name('tender-notice');
    // Route::get('procurement-notice/{slug}', 'NoticeController@show')->name('tender-notice.show');

    Route::get('search', 'SearchController@index')->name('search.index');
    Route::get('sitemap', 'SiteMapController@index')->name('sitemap.index');

    // Route::get('/reports/{slug}', ['uses' => 'ReportController@index', 'as' => 'report.category']);
    // Route::get('reports', 'ReportController@index')->name('report.index');
    // Route::get('agm-minutes', 'ReportController@agm')->name('agm.report');
    Route::get('refreshcaptcha', 'CaptchaController@refreshCaptcha');

    // Route::get('personal-banking/kbl-management-team', 'TeamController@index');
    // Route::get('business-banking/kbl-management-team', 'TeamController@index');
    // Route::get('trade-finance-treasury/kbl-management-team', 'TeamController@index');
    // Route::get('remittance/kbl-management-team', 'TeamController@index');
    // Route::get('team', 'TeamController@index')->name('team.index');
    Route::get('board-managements', 'TeamController@index')->name('team.index');
    Route::get('board-of-directors', 'TeamController@bod')->name('team.board-of-directors');
    Route::get('management-team/{id}', 'TeamController@show')->name('team.show');

    Route::get('board-management/{category_slug}', 'TeamController@categoryWiseTeam')->name('category.wise.team');

    #Redirect old URL
    // Route::get('', function () {
    //     return redirect('');
    // });

    // Route::get('bonus-share', 'BonusController@index')->name('bonus.index');
    // Route::post('bonus-share/search', 'BonusController@search')->name('bonus.search');
    // Route::get('tax-for-bonus-share-of-former-dev-bikash-bank', 'BonusController@dev')->name('bonus.dev');

    // Route::get('nav-details', ['uses' => 'NavController@index', 'as' => 'nav.index']);
    // Route::post('nav-details/table', ['uses' => 'NavController@table', 'as' => 'nav.table']);

    // Route::get('calculator', ['uses' => 'CalculatorController@index', 'as' => 'calculator.index']);
    // Route::get('calculator/sip', ['uses' => 'CalculatorController@sip', 'as' => 'calculator.sip']);
    // Route::post('calculator/calculate-sip', ['uses' => 'CalculatorController@calculateSip', 'as' => 'calculator.calculate-sip']);
    // Route::get('calculator/buy-sell', ['uses' => 'CalculatorController@buySell', 'as' => 'calculator.buysell']);
    // Route::post('calculator/calculate-buy-sell', ['uses' => 'CalculatorController@calculateBuySell', 'as' => 'calculator.calculate-buysell']);
    // Route::get('calculator/right-share', ['uses' => 'CalculatorController@rightShare', 'as' => 'calculator.right-share']);
    // Route::get('calculator/bonus-share', ['uses' => 'CalculatorController@bonusShare', 'as' => 'calculator.bonus-share']);

    //Route::get('interest-rates', ['uses' => 'InterestRatesController@index', 'as' => 'interest-rates.index']);

    // Route::get('pop-up/{slug?}', ['uses' => 'PopupController@show', 'as' => 'popup.view']);

    // Route::get('check-bank-guarantee', 'CheckBankGuaranteeController@index')->name('check-bank-guarantee.index');
    // Route::post('check-bank-guarantee', 'CheckBankGuaranteeController@result')->name('check-bank-guarantee.result');

    // Route::get('/surya-jyoti-cares', 'ContactController@jyotiCare')->name('jyoti-care.index');
    // Route::post('/surya-jyoti-cares', 'ContactController@jyotiCarePost')->name('jyoti-care.submit');

    // Route::post('/sitemap.xml', 'SitemapController@index')->name('sitemap.index');

    Route::get('internal-web', 'InternalWebController@index')->name('internal-web.index');
    Route::post('internal-web', 'InternalWebController@upload')->name('internal-web.upload');
    Route::get('internal-web/{slug}', 'InternalWebController@category')->name('internal-web.category');
    Route::get('internal-web/{year}/{category_slug}', 'InternalWebController@show')->name('internal-web.show');


    Route::get('{page?}/{slug}', 'ContentController@show')->name('content.show')->where('page', '^(?!.*admin).*$');
    Route::get('{slug}', 'ContentController@show')->name('content.show');
});
