<?php

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Helper\SettingHelper;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('site_settings') !== false) {
    SettingHelper::loadOptions();
}

Route::group(['middleware' => ['preventBackHistory', 'auth:admin'], 'prefix' => 'admin'], function () {
    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::get('error-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
    Route::post('reset_password', 'AdminListController@resetPassword')->name('admin.reset_password');
    Route::get('generatesitemap', 'GenerateSiteMapController@generate')->name('admin.generatesitemap.index');

    // Route::group(['prefix' => 'configuration'], function () {
    // Route::resource('fare-chart', 'FareChartController', ['as' => 'admin.configuration']);
    // Route::resource('payment-method', 'PaymentMethodController', ['as' => 'admin.configuration']);
    // Route::post('payment-method/change-status', array('as' => 'admin.configuration.payment-method.change-status', 'uses' => 'PaymentMethodController@changeStatus'));
    // });

    Route::resource('admin-type', 'AdminTypeController', ['as' => 'admin']);
    Route::post('admin-type/change-status', array('as' => 'admin.admin-type.change-status', 'uses' => 'AdminTypeController@changeStatus'));

    Route::prefix('admin-access')->group(function () {
        Route::get('/{admin_type_id}/create', 'AdminAccessController@create')->name('admin.admin-access.create');
        Route::post('/{admin_type_id}/store', 'AdminAccessController@store')->name('admin.admin-access.store');
    });

    Route::prefix('admin-list')->group(function () {
        Route::get('/{admin_type_id}', 'AdminListController@index')->name('admin.admin-list.index');
        Route::get('/{admin_type_id}/create', 'AdminListController@create')->name('admin.admin-list.create');
        Route::post('/{admin_type_id}/store', 'AdminListController@store')->name('admin.admin-list.store');
        Route::get('/{admin_type_id}/edit', 'AdminListController@edit')->name('admin.admin-list.edit');
        Route::post('change-status', 'AdminListController@changeStatus')->name('admin.admin-list.change-status');
        Route::delete('/{admin_type_id}/{admin_id}', 'AdminListController@destroy')->name('admin.admin-list.destroy');
    });

    Route::patch('/admin-list/{admin_type_id}/update', 'AdminListController@update')->name('admin.admin-list.update');

    Route::get('contents/block', ['uses' => 'ContentController@block', 'as' => 'admin.contents.block']);
    Route::post('contents/remove-block', ['uses' => 'ContentController@removeBlock', 'as' => 'admin.contents.remove-block']);
    Route::get('contents/remove-block-image', ['uses' => 'ContentController@removeBlockImage', 'as' => 'admin.contents.remove-block-image']);
    Route::post('contents/change-status', 'ContentController@changeStatus')->name('admin.contents.change-status');
    Route::post('contents/sort', array('as' => 'admin.contents.sort', 'uses' => 'ContentController@sort'));
    Route::post('contents/destroy-image/{id}', 'ContentController@destroyImage')->name('admin.contents.destroy-image');
    Route::resource('contents', 'ContentController', ['as' => 'admin']);

    Route::resource('news', 'NewsController', ['as' => 'admin']);
    Route::post('news/change-status', array('as' => 'admin.news.change-status', 'uses' => 'NewsController@changeStatus'));
    Route::post('news/sort', array('as' => 'admin.news.sort', 'uses' => 'NewsController@sort'));
    Route::post('news/destroy-image/{id}', 'NewsController@destroyImage')->name('admin.news.destroy-image');

    Route::post('news-categories/change-status', ['uses' => 'NewsCategoryController@changeStatus', 'as' => 'admin.news-category.change-status']);
    Route::post('news-categories/sort', ['uses' => 'NewsCategoryController@sort', 'as' => 'admin.news-category.sort']);
    Route::resource('news-categories', 'NewsCategoryController', ['as' => 'admin']);

    Route::resource('download', 'DownloadController', ['as' => 'admin']);
    Route::post('download/change-status', array('as' => 'admin.download.change-status', 'uses' => 'DownloadController@changeStatus'));
    Route::post('download/sort', array('as' => 'admin.download.sort', 'uses' => 'DownloadController@sort'));

    Route::resource('syllabus', 'SyllabusController', ['as' => 'admin']);
    Route::post('syllabus/change-status', array('as' => 'admin.syllabus.change-status', 'uses' => 'SyllabusController@changeStatus'));
    Route::post('syllabus/sort', array('as' => 'admin.syllabus.sort', 'uses' => 'SyllabusController@sort'));

    Route::resource('download-category', 'DownloadCategoryController', ['as' => 'admin']);
    Route::post('download-category/change-status', ['uses' => 'DownloadCategoryController@changeStatus', 'as' => 'admin.download-category.change-status']);
    Route::post('download-category/sort', ['uses' => 'DownloadCategoryController@sort', 'as' => 'admin.download-category.sort']);

    Route::resource('financial-report', 'FinancialReportController', ['as' => 'admin']);
    Route::post('financial-report/change-status', ['uses' => 'FinancialReportController@changeStatus', 'as' => 'admin.financial-report.change-status']);
    Route::post('financial-report/sort', ['uses' => 'FinancialReportController@sort', 'as' => 'admin.financial-report.sort']);

    Route::resource('financial-report-category', 'FinancialReportCategoryController', ['as' => 'admin']);
    Route::post('financial-report-category/change-status', ['uses' => 'FinancialReportCategoryController@changeStatus', 'as' => 'admin.financial-report-category.change-status']);
    Route::post('financial-report-category/sort', ['uses' => 'FinancialReportCategoryController@sort', 'as' => 'admin.financial-report-category.sort']);

    Route::resource('gallery', 'GalleryController', ['as' => 'admin']);
    Route::post('gallery/change-status', array('as' => 'admin.gallery.change-status', 'uses' => 'GalleryController@changeStatus'));
    Route::post('gallery/sort', array('as' => 'admin.gallery.sort', 'uses' => 'GalleryController@sort'));
    Route::post('gallery/destroy-image/{id}', 'GalleryController@destroyImage')->name('admin.gallery.destroy-image');

    Route::prefix('gallery/image')->group(function () {
        Route::get('/{gallery_id}', 'GalleryImageController@index')->name('admin.gallery-image.index');
        Route::get('/{gallery_id}/create', 'GalleryImageController@create')->name('admin.gallery-image.create');
        Route::post('/{gallery_id}/store', 'GalleryImageController@store')->name('admin.gallery-image.store');
        Route::delete('/{gallery_id}/{image_id}', 'GalleryImageController@destroy')->name('admin.gallery-image.destroy');
    });

    Route::resource('email-subscribe', 'EmailSubscribeController', ['as' => 'admin']);
    Route::get('/', 'EmailSubscribeController@index')->name('admin.email-subscribe.index');
    Route::delete('destroy/{id}', 'EmailSubscribeController@destroy')->name('admin.email-subscribe.destroy');
    Route::post('email-subscribe/change-status', array('as' => 'admin.email-subscribe.change-status', 'uses' => 'EmailSubscribeController@changeStatus'));
    Route::get('email-subscribe/create/mail', array('as' => 'admin.email-subscribe.create', 'uses' => 'EmailSubscribeController@create'));
    Route::post('email-subscribe/store', array('as' => 'admin.email-subscribe.store', 'uses' => 'EmailSubscribeController@store'));

    Route::resource('popup', 'PopupController', ['as' => 'admin']);
    Route::post('popup/change-status', array('as' => 'admin.popup.change-status', 'uses' => 'PopupController@changeStatus'));
    Route::get('/', 'PopupController@index')->name('admin.popup.index');
    Route::get('/create', 'PopupController@create')->name('admin.popup.create');
    Route::post('/store', 'PopupController@store')->name('admin.popup.store');
    Route::get('edit/{id}', 'PopupController@edit')->name('admin.popup.edit');
    Route::post('popup/sort', array('as' => 'admin.popup.sort', 'uses' => 'PopupController@sort'));
    Route::post('popup/destroy-image/{id}', 'PopupController@destroyImage')->name('admin.popup.destroy-image');

    Route::resource('roomlist', 'RoomlistController', ['as' => 'admin']);
    Route::get('/', 'RoomlistController@index')->name('admin.roomlist.index');
    Route::get('/create', 'RoomlistController@create')->name('admin.roomlist.create');
    Route::post('/store', 'RoomlistController@store')->name('admin.roomlist.store');
    Route::delete('destroy/{image_id}', 'RoomlistController@destroy')->name('admin.roomlist.destroy');
    Route::get('edit/{id}', 'RoomlistController@edit')->name('admin.roomlist.edit');
    Route::post('roomlist/change-status', 'RoomlistController@changeStatus')->name('admin.roomlist.change-status');

    Route::resource('testimonials', 'TestimonialsController', ['as' => 'admin']);
    Route::post('testimonials/sort', array('as' => 'admin.testimonials.sort', 'uses' => 'TestimonialsController@sort'));
    Route::post('testimonials/change-status', 'TestimonialsController@changeStatus')->name('admin.testimonials.change-status');

    Route::resource('destination', 'DestinationController', ['as' => 'admin']);
    Route::post('destination/change-status', array('as' => 'admin.destination.change-status', 'uses' => 'DestinationController@changeStatus'));
    Route::post('destination/sort', array('as' => 'admin.destination.sort', 'uses' => 'DestinationController@sort'));

    Route::resource('article', 'ArticleController', ['as' => 'admin']);
    Route::post('article/change-status', array('as' => 'admin.article.change-status', 'uses' => 'ArticleController@changeStatus'));
    Route::post('article/sort', array('as' => 'admin.article.sort', 'uses' => 'ArticleController@sort'));

    Route::prefix('articlecomment')->group(function () {
        Route::get('/{article_id}', 'ArticleCommentController@index')->name('admin.articlecomment.index');
        Route::get('/{article_id}/create', 'ArticleCommentController@create')->name('admin.articlecomment.create');
        Route::post('/{article_id}/store', 'ArticleCommentController@store')->name('admin.articlecomment.store');
        Route::get('/{article_id}/edit', 'ArticleCommentController@edit')->name('admin.articlecomment.edit');
        Route::post('/{article_id}/update/{articlecomment_id}', 'ArticleCommentController@update')->name('admin.articlecomment.update');
        Route::delete('/{article_id}/{articlecomment_id}', 'ArticleCommentController@destroy')->name('admin.articlecomment.destroy');
        Route::post('articlecomment/change-status', array('as' => 'admin.articlecomment.change-status', 'uses' => 'ArticleCommentController@changeStatus'));
    });

    Route::prefix('roomlist/image')->group(function () {
        Route::get('/{roomlist_id}', 'RoomlistImageController@index')->name('admin.roomlist-image.index');
        Route::get('/{roomlist_id}/create', 'RoomlistImageController@create')->name('admin.roomlist-image.create');
        Route::post('/{roomlist_id}/store', 'RoomlistImageController@store')->name('admin.roomlist-image.store');
        Route::delete('/{roomlist_id}/{image_id}', 'RoomlistImageController@destroy')->name('admin.roomlist-image.destroy');
    });

    Route::resource('activity', 'ActivityController', ['as' => 'admin']);
    Route::post('activity/change-status',  'ActivityController@changeStatus')->name('admin.activity.change-status');
    Route::get('/', 'ActivityController@index')->name('admin.activity.index');
    Route::get('/create', 'ActivityController@create')->name('admin.activity.create');
    Route::post('/store', 'ActivityController@store')->name('admin.activity.store');
    Route::delete('destroy/{image_id}', 'ActivityController@destroy')->name('admin.activity.destroy');
    Route::get('edit/{id}', 'ActivityController@edit')->name('admin.activity.edit');
    Route::post('activity/sort', array('as' => 'admin.activity.sort', 'uses' => 'ActivityController@sort'));

    Route::prefix('activityfaq')->group(function () {
        Route::get('/{activity_id}', 'ActivityFaqController@index')->name('admin.activityfaq.index');
        Route::get('/{activity_id}/create', 'ActivityFaqController@create')->name('admin.activityfaq.create');
        Route::post('/{activity_id}/store', 'ActivityFaqController@store')->name('admin.activityfaq.store');
        Route::get('/{activity_id}/edit/{activityfaq_id}', 'ActivityFaqController@edit')->name('admin.activityfaq.edit');
        Route::post('/{activity_id}/update/{activityfaq_id}', 'ActivityFaqController@update')->name('admin.activityfaq.update');
        Route::delete('/{activity_id}/{activityfaq_id}', 'ActivityFaqController@destroy')->name('admin.activityfaq.destroy');
        Route::post('activityfaq/change-status', array('as' => 'admin.activityfaq.change-status', 'uses' => 'ActivityFaqController@changeStatus'));
    });

    Route::resource('imageresize', 'ImageresizeController', ['as' => 'admin']);
    Route::post('/change-status', array('as' => 'admin.imageresize.change-status', 'uses' => 'imageresizeController@changeStatus'));
    Route::get('/', 'ImageresizeController@index')->name('admin.imageresize.index');
    Route::get('/create', 'ImageresizeController@create')->name('admin.imageresize.create');
    Route::post('/store', 'ImageresizeController@store')->name('admin.imageresize.store');
    Route::delete('destroy/{image_id}', 'ImageresizeController@destroy')->name('admin.imageresize.destroy');
    Route::get('edit/{id}', 'ImageresizeController@edit')->name('admin.imageresize.edit');

    Route::resource('memberType', 'MemberTypeController', ['as' => 'admin']);
    Route::post('member/change-status', array('as' => 'admin.members.change-status', 'uses' => 'MemberTypeController@changeStatus'));
    Route::get('/', 'MemberTypeController@index')->name('admin.memberType.index');
    Route::get('/create', 'MemberTypeController@create')->name('admin.memberType.create');
    Route::post('/store', 'MemberTypeController@store')->name('admin.memberType.store');
    Route::delete('destroy/{image_id}', 'MemberTypeController@destroy')->name('admin.memberType.destroy');
    Route::get('edit/{id}', 'MemberTypeController@edit')->name('admin.memberType.edit');
    Route::post('update/{id}', 'MemberTypeController@update')->name('admin.memberType.update');

    Route::resource('members', 'MembersController', ['as' => 'admin']);
    Route::post('members/change-status', array('as' => 'admin.members.change-status', 'uses' => 'MembersController@changeStatus'));
    Route::get('/', 'MembersController@index')->name('admin.members.index');
    Route::get('/create', 'MembersController@create')->name('admin.members.create');
    Route::post('/store', 'MembersController@store')->name('admin.members.store');
    Route::delete('destroy/{id}', 'MembersController@destroy')->name('admin.members.destroy');
    Route::get('edit/{id}', 'MembersController@edit')->name('admin.members.edit');
    Route::post('update/{id}', 'MembersController@update')->name('admin.members.update');

    Route::resource('setting', 'SiteSettingController', ['as' => 'admin']);
    Route::get('/', 'SiteSettingController@index')->name('admin.setting.index');
    Route::post('setting/change-status', array('as' => 'admin.setting.change-status', 'uses' => 'SiteSettingController@changeStatus'));

    Route::get('/create', 'SiteSettingController@create')->name('admin.setting.create');
    Route::post('/store', 'SiteSettingController@store')->name('admin.setting.store');
    Route::delete('destroy/{id}', 'SiteSettingController@destroy')->name('admin.setting.destroy');
    Route::get('edit/{id}', 'SiteSettingController@edit')->name('admin.setting.edit');
    Route::post('/{id}/update', 'SiteSettingController@update')->name('admin.setting.update');

    Route::resource('gallery-video', 'GalleryVideoController', ['as' => 'admin']);
    Route::post('gallery-video/change-status', array('as' => 'admin.gallery-video.change-status', 'uses' => 'GalleryVideoController@changeStatus'));
    Route::post('gallery-video/sort', array('as' => 'admin.gallery-video.sort', 'uses' => 'GalleryVideoController@sort'));

    // Route::resource('notice', 'NoticeController', ['as' => 'admin']);
    // Route::post('notice/change-status', array('as' => 'admin.notice.change-status', 'uses' => 'NoticeController@changeStatus'));
    // Route::post('notice/sort', array('as' => 'admin.notice.sort', 'uses' => 'NoticeController@sort'));

    Route::resource('banner', 'BannerController', ['as' => 'admin']);
    Route::post('banner/change-status', array('as' => 'admin.banner.change-status', 'uses' => 'BannerController@changeStatus'));
    Route::post('banner/sort', array('as' => 'admin.banner.sort', 'uses' => 'BannerController@sort'));
    Route::post('banner/destroy-image/{id}', 'BannerController@destroyImage')->name('admin.banner.destroy-image');

    Route::post('blog/categories/change-status', ['as' => 'admin.blog-category.change-status', 'uses' => 'BlogCategoryController@changeStatus']);
    Route::post('blog-catetgories/sort', array('as' => 'admin.blog-categories.sort', 'uses' => 'BlogCategoryController@sort'));
    Route::resource('blog-categories', 'BlogCategoryController', ['as' => 'admin']);
    Route::get('blogs/block', ['uses' => 'BlogController@block', 'as' => 'admin.blogs.block']);
    Route::post('blogs/remove-block', ['uses' => 'BlogController@removeBlock', 'as' => 'admin.blogs.remove-block']);
    Route::get('blogs/remove-block-image', ['uses' => 'BlogController@removeBlockImage', 'as' => 'admin.blogs.remove-block-image']);

    Route::post('blogs/destroy-image/{id}', 'BlogController@destroyImage')->name('admin.blogs.destroy-image');
    Route::post('blogs/change-status', ['as' => 'admin.blog.change-status', 'uses' => 'BlogController@changeStatus']);
    Route::resource('blogs', 'BlogController', ['as' => 'admin']);

    Route::post('stories/destroy-image/{id}', 'BlogController@destroyImage')->name('admin.stories.destroy-image');
    Route::post('stories/change-status', ['as' => 'admin.stories.change-status', 'uses' => 'BlogController@changeStatus']);
    Route::resource('stories', 'BlogController', ['as' => 'admin']);

    Route::get('seos', 'SeoController@index')->name('admin.seos.index');
    Route::get('seos/create', 'SeoController@create')->name('admin.seos.create');
    Route::post('seos', 'SeoController@store')->name('admin.seos.store');
    Route::get('seos/{seo}/edit', 'SeoController@edit')->name('admin.seos.edit');
    Route::put('seos/{seo}', 'SeoController@update')->name('admin.seos.update');
    Route::delete('seos/{seo}', 'SeoController@destroy')->name('admin.seos.destroy');

    Route::resource('packages', 'PackageController', ['as' => 'admin']);
    Route::get('/', 'PackageController@index')->name('admin.packages.index');
    Route::post('packages/change-status', array('as' => 'admin.packages.change-status', 'uses' => 'PackageController@changeStatus'));
    Route::post('packages/sort', array('as' => 'admin.packages.sort', 'uses' => 'PackageController@sort'));
    Route::post('packages/change-status', array('as' => 'admin.packages.change-status', 'uses' => 'PackageController@changeStatus'));

    Route::resource('booking', 'BookingController', ['as' => 'admin']);
    Route::get('/', 'BookingController@index')->name('admin.booking.index');
    Route::post('booking/change-status', array('as' => 'admin.booking.change-status', 'uses' => 'BookingController@changeStatus'));
    Route::post('booking/sort', array('as' => 'admin.booking.sort', 'uses' => 'BookingController@sort'));
    Route::post('booking/change-status', array('as' => 'admin.booking.change-status', 'uses' => 'BookingController@changeStatus'));
    Route::get('/bookingview/{booking_id}', array('as' => 'admin.booking.bookingview', 'uses' => 'BookingController@show'));

    Route::prefix('packagebanner')->group(function () {
        Route::get('/{package_id}', 'PackageBannerController@index')->name('admin.packagebanner.index');
        Route::get('/{package_id}/create', 'PackageBannerController@create')->name('admin.packagebanner.create');
        Route::post('/{package_id}/store', 'PackageBannerController@store')->name('admin.packagebanner.store');
        Route::get('/{package_id}/edit', 'PackageBannerController@edit')->name('admin.packagebanner.edit');
        Route::post('/{package_id}/update/{packagebanner_id}', 'PackageBannerController@update')->name('admin.packagebanner.update');
        Route::delete('/{package_id}/{packagebanner_id}', 'PackageBannerController@destroy')->name('admin.packagebanner.destroy');
        Route::post('packagebanner/change-status', array('as' => 'admin.packagebanner.change-status', 'uses' => 'PackageBannerController@changeStatus'));
    });

    Route::prefix('packagepricerange')->group(function () {
        Route::get('/{package_id}', 'PackagePriceRangeController@index')->name('admin.packagepricerange.index');
        Route::get('/{package_id}/create', 'PackagePriceRangeController@create')->name('admin.packagepricerange.create');
        Route::post('/{package_id}/store', 'PackagePriceRangeController@store')->name('admin.packagepricerange.store');
        Route::get('/{package_id}/edit', 'PackagePriceRangeController@edit')->name('admin.packagepricerange.edit');
        Route::post('/{package_id}/update/{packagepricerange_id}', 'PackagePriceRangeController@update')->name('admin.packagepricerange.update');
        Route::delete('/{package_id}/{packagepricerange_id}', 'PackagePriceRangeController@destroy')->name('admin.packagepricerange.destroy');
        Route::post('packagepricerange/change-status', array('as' => 'admin.packagepricerange.change-status', 'uses' => 'PackagePriceRangeController@changeStatus'));
    });

    Route::prefix('packageroute')->group(function () {
        Route::get('/{package_id}', 'PackageRouteController@create')->name('admin.packageroute.create');
        Route::post('/{package_id}/store', 'PackageRouteController@store')->name('admin.packageroute.store');
        Route::post('/{package_id}/update/{packageroute_id}', 'PackageRouteController@update')->name('admin.packageroute.update');
    });

    Route::prefix('itinerary')->group(function () {
        Route::get('/{package_id}', 'ItineraryController@index')->name('admin.itinerary.index');
        Route::get('/{package_id}/create', 'ItineraryController@create')->name('admin.itinerary.create');
        Route::post('/{package_id}/store', 'ItineraryController@store')->name('admin.itinerary.store');
        Route::get('/{package_id}/edit', 'ItineraryController@edit')->name('admin.itinerary.edit');
        Route::post('/{package_id}/update/{itinerary_id}', 'ItineraryController@update')->name('admin.itinerary.update');
        Route::delete('/{package_id}/{itinerary_id}', 'ItineraryController@destroy')->name('admin.itinerary.destroy');
        Route::post('itinerary/change-status', array('as' => 'admin.itinerary.change-status', 'uses' => 'ItineraryController@changeStatus'));
    });

    Route::prefix('fixeddeparture')->group(function () {
        Route::get('/{package_id}', 'FixedDepartureController@index')->name('admin.fixeddeparture.index');
        Route::get('/{package_id}/create', 'FixedDepartureController@create')->name('admin.fixeddeparture.create');
        Route::post('/{package_id}/store', 'FixedDepartureController@store')->name('admin.fixeddeparture.store');
        Route::get('/{package_id}/edit', 'FixedDepartureController@edit')->name('admin.fixeddeparture.edit');
        Route::post('/{package_id}/update/{fixeddeparture_id}', 'FixedDepartureController@update')->name('admin.fixeddeparture.update');
        Route::delete('/{package_id}/{fixeddeparture_id}', 'FixedDepartureController@destroy')->name('admin.fixeddeparture.destroy');
        Route::post('fixeddeparture/change-status', array('as' => 'admin.fixeddeparture.change-status', 'uses' => 'FixedDepartureController@changeStatus'));
    });

    Route::resource('faq-category', 'FaqCategoryController', ['as' => 'admin']);
    Route::post('faq-category/change-status', array('as' => 'admin.faq-category.change-status', 'uses' => 'FaqCategoryController@changeStatus'));

    Route::post('faq/sort', ['as' => 'admin.faq.sort', 'uses' => 'FaqController@sort']);

    Route::prefix('faq-category/{faq_category_id}')->group(function () {
        Route::resource('faq', 'FaqController', ['as' => 'admin']);
        Route::post('faq/change-status', array('as' => 'admin.faq.change-status', 'uses' => 'FaqController@changeStatus'));
    });

    Route::post('department/change-status', array('as' => 'admin.department.change-status', 'uses' => 'DepartmentController@changeStatus'));
    Route::post('department/sort', array('as' => 'admin.department.sort', 'uses' => 'DepartmentController@sort'));
    Route::resource('department', 'DepartmentController', ['as' => 'admin']);

    Route::get('interest-rates/delete/{batch}', 'InterestRatesController@destroy')->name('admin.interest-rates.delete');
    Route::get('interest-rates/edit/{batch}', 'InterestRatesController@edit')->name('admin.interest-rates.edit-active');
    Route::post('interest-rates/edit/{batch}', 'InterestRatesController@update')->name('admin.interest-rates.update-active');
    Route::post('interest-batch/toggle-status/{batch}', 'InterestRatesController@toggleStatus')->name('admin.interest-batch.toggle-status');
    Route::resource('interest-rates', 'InterestRatesController', ['as' => 'admin']);

    Route::post('doctor/change-status', array('as' => 'admin.doctor.change-status', 'uses' => 'DoctorController@changeStatus'));
    Route::post('doctor/sort', array('as' => 'admin.doctor.sort', 'uses' => 'DoctorController@sort'));
    Route::resource('doctor', 'DoctorController', ['as' => 'admin']);

    Route::post('doctor-time-slot/change-status', array('as' => 'admin.doctor-time-slot.change-status', 'uses' => 'DoctorTimeSlotController@changeStatus'));
    Route::post('doctor-time-slot/sort', array('as' => 'admin.doctor-time-slot.sort', 'uses' => 'DoctorTimeSlotController@sort'));
    Route::resource('doctor-time-slot', 'DoctorTimeSlotController', ['as' => 'admin']);

    Route::resource('appointments', 'AppointmentController', ['as' => 'admin']);

    Route::post('package-categories/change-status', array('as' => 'admin.package-categories.change-status', 'uses' => 'PackageCategoryController@changeStatus'));
    Route::post('package-categories/sort', array('as' => 'admin.package-categories.sort', 'uses' => 'PackageCategoryController@sort'));
    Route::resource('package-categories', 'PackageCategoryController', ['as' => 'admin']);

    Route::resource('module', 'ModuleController', ['as' => 'admin']);
    Route::post('module/sort', ['as' => 'admin.module.sort', 'uses' => 'ModuleController@sort']);
    Route::post('module/toggle-menu', ['as' => 'admin.module.toggle-menu', 'uses' => 'ModuleController@toggleMenu']);

    Route::post('menu/change-status', ['as' => 'admin.menu.change-status', 'uses' => 'MenuController@changeStatus']);
    Route::post('menu/sort', array('as' => 'admin.menu.sort', 'uses' => 'MenuController@sort'));
    Route::resource('menu', 'MenuController', ['as' => 'admin']);

    Route::prefix('menu')->group(function () {
        Route::get('/{menu_id}/menu-item', 'MenuItemController@index')->name('admin.menu-item.index');
        Route::get('/{menu_id}/menu-item/create', 'MenuItemController@create')->name('admin.menu-item.create');
        Route::post('/{menu_id}/menu-item/store', 'MenuItemController@store')->name('admin.menu-item.store');
        Route::get('/{menu_id}/menu-item/edit/{id}', 'MenuItemController@edit')->name('admin.menu-item.edit');
        Route::post('/{menu_id}/menu-item/update/{id}', 'MenuItemController@update')->name('admin.menu-item.update');
        Route::delete('/{menu_id}/menu-item/{id}', 'MenuItemController@destroy')->name('admin.menu-item.destroy');
        Route::post('/{menu_id}/menu-item/sort', 'MenuItemController@sort')->name('admin.menu-item.sort');
        Route::post('/{menu_id}/menu-item/destroy-image/{id}', 'MenuItemController@destroyImage')->name('admin.menu-item.destroy-image');
    });

    Route::resource('associates', 'AssociateController', ['as' => 'admin']);
    Route::post('associates/sort', ['as' => 'admin.associates.sort', 'uses' => 'AssociateController@sort']);
    Route::post('associates/change-status', ['as' => 'admin.associates.change-status', 'uses' => 'AssociateController@changeStatus']);

    Route::resource('products-and-services', 'ProductAndServiceController', ['as' => 'admin']);
    Route::post('products-and-services/sort', ['as' => 'admin.products-and-services.sort', 'uses' => 'ProductAndServiceController@sort']);
    Route::post('proudcts-and-services/change-status', ['as' => 'admin.products-and-services.change-status', 'uses' => 'ProductAndServiceController@changeStatus']);

    Route::resource('products-and-services-category', 'ProductAndServiceCategoryController', ['as' => 'admin']);
    Route::post('products-and-services-category/sort', ['as' => 'admin.products-and-services-category.sort', 'uses' => 'ProductAndServiceCategoryController@sort']);
    Route::post('proudcts-and-services-category/change-status', ['as' => 'admin.products-and-services-category.change-status', 'uses' => 'ProductAndServiceCategoryController@changeStatus']);

    Route::resource('tender-notice', 'TenderNoticeController', ['as' => 'admin']);
    Route::post('tender-notice/sort', ['as' => 'admin.tender-notice.sort', 'uses' => 'TenderNoticeController@sort']);
    Route::post('tender-notice/change-status', ['as' => 'admin.tender-notice.change-status', 'uses' => 'TenderNoticeController@changeStatus']);
    Route::post('tender-release/destroy-image/{id}', 'TenderNoticeController@destroyImage')->name('admin.tender-notice.destroy-image');

    Route::post('press-release/sort', ['as' => 'admin.press-release.sort', 'uses' => 'PressReleaseController@sort']);
    Route::post('press-release/change-status', ['as' => 'admin.press-release.change-status', 'uses' => 'PressReleaseController@changeStatus']);
    Route::post('press-release/destroy-image/{id}', 'PressReleaseController@destroyImage')->name('admin.press-release.destroy-image');
    Route::resource('notice', 'PressReleaseController', ['as' => 'admin']);

    Route::get('atm-location/district', ['as' => 'admin.atm-location.district', 'uses' => 'AtmLocationController@getDistrict']);
    Route::post('atm-location/sort', ['as' => 'admin.atm-location.sort', 'uses' => 'AtmLocationController@sort']);
    Route::post('atm-location/change-status', ['as' => 'admin.atm-location.change-status', 'uses' => 'AtmLocationController@changeStatus']);
    Route::resource('atm-location', 'AtmLocationController', ['as' => 'admin']);

    Route::get('remittance/district', ['as' => 'admin.remittance.district', 'uses' => 'RemittanceController@getDistrict']);
    Route::resource('remittance', 'RemittanceController', ['as' => 'admin']);
    Route::post('remittance/sort', ['as' => 'admin.remittance.sort', 'uses' => 'RemittanceController@sort']);
    Route::post('remittance/change-status', ['as' => 'admin.remittance.change-status', 'uses' => 'RemittanceController@changeStatus']);

    Route::get('remittance-alliances/kumari-paying-alliance', ['uses' => 'RemittanceController@kumari', 'as' => 'admin.remit-kumari']);
    Route::get("remittance-alliances/remit-service", ['uses' => 'RemittanceController@service', 'as' => 'admin.remit-service']);
    Route::get('remittance-alliances/oversea-alliance', ['uses' => 'RemittanceController@overseaAlliance', 'as' => 'admin.remit-oversea-alliance']);

    Route::get('branch-directory/district', ['as' => 'admin.branch-directory.district', 'uses' => 'BranchDirectoryController@getDistrict']);
    Route::resource('branch-directory', 'BranchDirectoryController', ['as' => 'admin']);
    Route::post('branch-directory/sort', ['as' => 'admin.branch-directory.sort', 'uses' => 'BranchDirectoryController@sort']);
    Route::post('branch-directory/change-status', ['as' => 'admin.branch-directory.change-status', 'uses' => 'BranchDirectoryController@changeStatus']);
    Route::post('branch/destroy-image/{id}', 'BranchDirectoryController@destroyImage')->name('admin.branch.destroy-image');

    Route::post('account-type/destroy-image/{id}', 'AccountTypeController@destroyImage')->name('admin.account-type.destroy-image');
    Route::post('account-type/sort', ['as' => 'admin.account-type.sort', 'uses' => 'AccountTypeController@sort']);
    Route::post('account-type/change-status', ['as' => 'admin.account-type.change-status', 'uses' => 'AccountTypeController@changeStatus']);
    Route::get('account-type/enquiry/export', ['uses' => 'AccountTypeController@export', 'as' => 'admin.account-type.export']);
    Route::get('account-type/enquiry/{id}', ['uses' => 'AccountTypeController@enquiry', 'as' => 'admin.account-type.enquiry']);
    Route::resource('account-type', 'AccountTypeController', ['as' => 'admin']);

    Route::post('account-type-category/destroy-image/{id}', 'AccountTypeCategoryController@destroyImage')->name('admin.account-type-category.destroy-image');
    Route::post('account-type-category/sort', ['as' => 'admin.account-type-category.sort', 'uses' => 'AccountTypeCategoryController@sort']);
    Route::post('account-type-category/change-status', ['as' => 'admin.account-type-category.change-status', 'uses' => 'AccountTypeCategoryController@changeStatus']);
    Route::resource('account-type-category', 'AccountTypeCategoryController', ['as' => 'admin']);


    Route::post('offers/destroy-image/{id}', 'OffersController@destroyImage')->name('admin.offers.destroy-image');
    Route::resource('offers', 'OffersController', ['as' => 'admin']);
    Route::post('offers/sort', ['as' => 'admin.offers.sort', 'uses' => 'OffersController@sort']);
    Route::post('offers/change-status', ['as' => 'admin.offers.change-status', 'uses' => 'OffersController@changeStatus']);

    Route::resource('services', 'ServicesController', ['as' => 'admin']);
    Route::post('services/sort', ['as' => 'admin.services.sort', 'uses' => 'ServicesController@sort']);
    Route::post('services/change-status', ['as' => 'admin.services.change-status', 'uses' => 'ServicesController@changeStatus']);
    Route::post('services/destroy-image/{id}', 'ServicesController@destroyImage')->name('admin.services.destroy-image');

    Route::resource('partners', 'PartnersController', ['as' => 'admin']);
    Route::post('partners/sort', ['as' => 'admin.partners.sort', 'uses' => 'PartnersController@sort']);
    Route::post('partners/change-status', ['as' => 'admin.partners.change-status', 'uses' => 'PartnersController@changeStatus']);

    Route::post('partners/destroy-image/{id}', 'PartnersController@destroyImage')->name('admin.partner.destroy-image');

    Route::resource('body-menu', 'BodyMenuController', ['as' => 'admin']);
    Route::post('body-menu/sort', ['as' => 'admin.body-menu.sort', 'uses' => 'BodyMenuController@sort']);
    Route::post('body-menu/change-status', ['as' => 'admin.body-menu.change-status', 'uses' => 'BodyMenuController@changeStatus']);
    Route::post('body-menu/destroy-image/{id}', 'BodyMenuController@destroyImage')->name('admin.body-menu.destroy-image');

    Route::resource('remittance-alliance', 'RemittanceAllianceController', ['as' => 'admin']);
    Route::post('remittance-alliance/sort', ['as' => 'admin.remittance-alliance.sort', 'uses' => 'RemittanceAllianceController@sort']);
    Route::post('remittance-alliance/change-status', ['as' => 'admin.remittance-alliance.change-status', 'uses' => 'RemittanceAllianceController@changeStatus']);
    Route::resource('remittance-alliance-request', 'RemittanceAllianceRequestController', ['as' => 'admin']);
    Route::resource('remittance-alliance-contact', 'RemittanceAllianceContactController', ['as' => 'admin']);

    Route::resource('contact', 'ContactController', ['as' => 'admin']);
    Route::resource('jyoti-care', 'JyotiCareController', ['as' => 'admin']);
    // Route::resource('jyoti-care', 'JyotiCareController', ['as' => 'admin']);



    Route::resource('remittance-info', 'RemittanceInfoController', ['as' => 'admin']);

    Route::resource('layout', 'LayoutController', ['as' => 'admin']);

    Route::resource('department', 'DepartmentController', ['as' => 'admin']);
    Route::post('department/sort', ['as' => 'admin.department.sort', 'uses' => 'DepartmentController@sort']);
    Route::post('department/change-status', ['as' => 'admin.department.change-status', 'uses' => 'DepartmentController@changeStatus']);

    Route::resource('grievance', 'GrievanceController', ['as' => 'admin']);

    Route::resource('career', 'CareerController', ['as' => 'admin']);
    Route::post('career/sort', ['as' => 'admin.career.sort', 'uses' => 'CareerController@sort']);
    Route::post('career/change-status', ['as' => 'admin.career.change-status', 'uses' => 'CareerController@changeStatus']);

    Route::resource('ads', 'AdvertisementController', ['as' => 'admin']);
    Route::post('ads/change-status', array('as' => 'admin.ads.change-status', 'uses' => 'AdvertisementController@changeStatus'));

    Route::resource('applicant', 'ApplicantController', ['as' => 'admin']);

    Route::resource('log', 'LogController', ['only' => ['index', 'show']]);

    Route::get('email/logs', 'EmailLogController@index')->name('email.logs.index');
    Route::get('email/logs/{log}', 'EmailLogController@show')->name('email.logs.show');

    Route::get('import/atm', ['uses' => 'ImportController@importAtm', 'as' => 'admin.import.import-atm']);
    Route::post('import/store-atm', ['uses' => 'ImportController@storeAtm', 'as' => 'admin.import.store-atm']);

    Route::get('import/branch', ['uses' => 'ImportController@importBranch', 'as' => 'admin.import.import-branch']);
    Route::post('import/store-branch', ['uses' => 'ImportController@storeBranch', 'as' => 'admin.import.store-branch']);

    Route::get('import/download', ['uses' => 'ImportController@importDownload', 'as' => 'admin.import.import-download']);
    Route::post('import/store-download', ['uses' => 'ImportController@storeDownload', 'as' => 'admin.import.store-download']);

    Route::get('import/financial-report', ['uses' => 'ImportController@importFinancialReport', 'as' => 'admin.import.import-financial-report']);
    Route::post('import/store-financial-report', ['uses' => 'ImportController@storeFinancialReport', 'as' => 'admin.import.store-financial-report']);

    Route::get('import/remit-kumari', ['uses' => 'ImportController@importRemitKumari', 'as' => 'admin.import.import-remit-kumari']);
    Route::post('import/store-remit-kumari', ['uses' => 'ImportController@storeRemitKumari', 'as' => 'admin.import.store-remit-kumari']);

    Route::get('analytics', ['uses' => 'DashboardController@analytics', 'as' => 'admin.google-analytics']);

    Route::post('team-category/sort', ['as' => 'admin.team-category.sort', 'uses' => 'TeamCategoryController@sort']);
    Route::post('team-category/change-status', ['as' => 'admin.team-category.change-status', 'uses' => 'TeamCategoryController@changeStatus']);
    Route::resource('team-category', 'TeamCategoryController', ['as' => 'admin']);

    Route::post('team/sort', ['as' => 'admin.team.sort', 'uses' => 'TeamController@sort']);
    Route::post('team/change-status', ['as' => 'admin.team.change-status', 'uses' => 'TeamController@changeStatus']);
    Route::resource('team', 'TeamController', ['as' => 'admin']);
    Route::get('team/destroy-image/{id}', 'TeamController@destroyImage')->name('admin.team.destroy-image');


    Route::post('agm-report/change-status', ['uses' => 'AgmReportController@changeStatus', 'as' => 'admin.agm-report.change-status']);
    Route::post('agm-report/sort', ['uses' => 'AgmReportController@sort', 'as' => 'admin.agm-report.sort']);
    Route::resource('agm-report', 'AgmReportController', ['as' => 'admin']);

    Route::post('agm-report-category/change-status', ['uses' => 'AgmReportCategoryController@changeStatus', 'as' => 'admin.agm-report-category.change-status']);
    Route::post('agm-report-category/sort', ['uses' => 'AgmReportCategoryController@sort', 'as' => 'admin.agm-report-category.sort']);
    Route::resource('agm-report-category', 'AgmReportCategoryController', ['as' => 'admin']);

    Route::post('bonus/change-status', ['uses' => 'BonusController@changeStatus', 'as' => 'admin.bonus.change-status']);
    Route::get('bonus/import', ['uses' => 'BonusController@import', 'as' => 'admin.bonus.import']);
    Route::post('bonus/import', ['uses' => 'BonusController@saveImport', 'as' => 'admin.bonus.save-import']);
    Route::get('bonus/flush', ['uses' => 'BonusController@truncate', 'as' => 'admin.bonus.flush']);
    Route::resource('bonus', 'BonusController', ['as' => 'admin']);

    Route::post('bonus-category/change-status', ['uses' => 'BonusCategoryController@changeStatus', 'as' => 'admin.bonus-category.change-status']);
    Route::resource('bonus-category', 'BonusCategoryController', ['as' => 'admin']);

    Route::post('nav-categories/sort', ['uses' => 'NavCategoryController@sort', 'as' => 'admin.nav-categories.sort']);
    Route::post('nav-categories/change-status', ['uses' => 'NavCategoryController@changeStatus', 'as' => 'admin.nav-categories.change-status']);
    Route::resource('nav-categories', 'NavCategoryController', ['as' => 'admin']);

    Route::post('navs/sort', ['uses' => 'NavController@sort', 'as' => 'admin.navs.sort']);
    Route::post('navs/change-status', ['uses' => 'NavController@changeStatus', 'as' => 'admin.navs.change-status']);
    Route::get('navs/import', ['uses' => 'NavController@import', 'as' => 'admin.navs.import']);
    Route::post('navs/import', ['uses' => 'NavController@storeImport', 'as' => 'admin.navs.store-import']);
    Route::get('navs/flush', ['uses' => 'NavController@truncate', 'as' => 'admin.navs.flush']);
    Route::resource('navs', 'NavController', ['as' => 'admin']);

    Route::prefix('forex')->group(function () {
        Route::get('import', ['uses' => 'ForexController@import', 'as' => 'admin.forex.import']);
        Route::post('import', ['uses' => 'ForexController@storeImport', 'as' => 'admin.forex.store-import']);
    });
    Route::resource('forex', 'ForexController', ['as' => 'admin']);

    Route::prefix('stock-watch')->group(function () {
        Route::post('/change-status', ['uses' => 'StockInfoController@changeStatus', 'as' => 'admin.stock-watch.change-status']);
    });
    Route::resource('stock-watch', 'StockInfoController', ['as' => 'admin']);

    Route::get('check-bank-guarantee', ['uses' => 'CheckBankGuaranteeController@index', 'as' => 'admin.check-bank-guarantee.index']);
    Route::get('check-bank-guarantee/import', ['uses' => 'CheckBankGuaranteeController@import', 'as' => 'admin.check-bank-guarantee.import']);
    Route::post('check-bank-guarantee/import', ['uses' => 'CheckBankGuaranteeController@saveImport', 'as' => 'admin.check-bank-guarantee.save-import']);
    Route::get('check-bank-guarantee/flush', ['uses' => 'CheckBankGuaranteeController@truncate', 'as' => 'admin.check-bank-guarantee.flush']);

    Route::get('check-bank-guarantee/view/{id}', ['uses' => 'CheckBankGuaranteeController@view', 'as' => 'admin.check-bank-guarantee.view']);
    Route::get('check-bank-guarantee/edit/{id}', ['uses' => 'CheckBankGuaranteeController@edit', 'as' => 'admin.check-bank-guarantee.edit']);
    Route::get('check-bank-guarantee/update/{id}', ['uses' => 'CheckBankGuaranteeController@update', 'as' => 'admin.check-bank-guarantee.update']);
    Route::get('check-bank-guarantee/delete/{id}', ['uses' => 'CheckBankGuaranteeController@destroy', 'as' => 'admin.check-bank-guarantee.destroy']);


    Route::post('statistics/sort', ['uses' => 'StatisticsController@sort', 'as' => 'admin.statistics.sort']);
    Route::post('statistics/change-status', ['uses' => 'StatisticsController@changeStatus', 'as' => 'admin.statistics.change-status']);
    Route::resource('statistics', 'StatisticsController', ['as' => 'admin']);

    Route::post('loan-graph/sort', ['uses' => 'LoanGraphController@sort', 'as' => 'admin.loan-graph.sort']);
    Route::post('loan-graph/change-status', ['uses' => 'LoanGraphController@changeStatus', 'as' => 'admin.loan-graph.change-status']);
    Route::resource('loan-graph', 'LoanGraphController', ['as' => 'admin']);

    Route::resource('internal-web-category', 'InternalWebCategoryConroller', ['as' => 'admin']);
    Route::post('internal-web-category/change-status', ['uses' => 'InternalWebCategoryConroller@changeStatus', 'as' => 'admin.internal-web-category.change-status']);
    Route::post('internal-web-category/sort', ['uses' => 'InternalWebCategoryConroller@sort', 'as' => 'admin.internal-web-category.sort']);

    Route::resource('internal-web', 'InternalWebConroller', ['as' => 'admin']);
    Route::post('internal-web/change-status', array('as' => 'admin.internal-web.change-status', 'uses' => 'InternalWebConroller@changeStatus'));
    Route::post('internal-web/sort', array('as' => 'admin.internal-web.sort', 'uses' => 'InternalWebConroller@sort'));


});
