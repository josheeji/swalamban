<?php

namespace App\Http\ViewComposers;

use App\Repositories\CasteRepository;
use App\Repositories\CountryRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ReligionRepository;
use App\Repositories\UserRepository;
use Illuminate\View\View;

use Illuminate\Support\Facades\Route;

class HeaderComposer
{
    /**
     * Create a new header composer.
     *
     * @param UserRepository $users
     * @param CountryRepository $countries
     * @param ReligionRepository $religions
     * @param CasteRepository $castes
     * @param NotificationRepository $notifications
     * @return void
     */
    public function __construct(
        UserRepository $users,
        CountryRepository $countries,
        ReligionRepository $religions,
        CasteRepository $castes,
        NotificationRepository $notifications
    ){
        $this->users = $users;
        $this->countries = $countries;
        $this->religions = $religions;
        $this->castes = $castes;
        $this->notifications = $notifications;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $authUser = auth()->guard('user')->user();
        if($authUser){
            $view->withAuthUser($authUser)
                ->withNotificationCount($authUser->notifications()->where('is_seen', '=', 0)->count())
                ->withNotifications($authUser->notifications()->orderBy('created_at', 'desc')->take(5)->get())
                ->withCurrentRoute(Route::currentRouteName())
                ->withPartnerGender($authUser->gender == 1 ? 2 : 1);
        } else {
            $view->withAuthUser($authUser);
        }

    }
}
