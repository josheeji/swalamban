<?php

namespace App\Http\Controllers;

use App\Helper\MediaHelper;
use App\Helper\SettingHelper;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\JyotiCareRequest;
use App\Jobs\ContactMailJob;
use App\Mail\MailForwarded;
use App\Mail\MailReceived;
use App\Models\Contact;
use App\Models\JyotiCare;
use App\Models\MenuItems;
use App\Models\SiteSetting;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __construct(SiteSetting $site_setting)
    {
        $this->site_setting = $site_setting;
    }
    public function index()
    {
        $content = Contact::where('is_active', 1)->first();
        $menu = MenuItems::where('link_url',request()->path())->first();
        SEOMeta::setDescription($menu->title ??  'Swabalamban Laghubitta Bittiya Sanstha Ltd.');

        OpenGraph::setDescription($menu->title ??   'Swabalamban Laghubitta Bittiya Sanstha Ltd.');
        OpenGraph::setTitle($menu->title ?? SettingHelper::setting('site_title'));
        OpenGraph::addImages([isset($menu->image) ? asset('storage/' . @$menu->image) : asset('swabalamban/images/titlebg.jpg')]);
        OpenGraph::setUrl(url()->current());

        return view('contact.index')
            ->withContent($content)
            ->withMenu($menu);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->except(['captcha']);
        DB::beginTransaction();
        try {
            $data['name'] = $data['f_name'] . ' '. $data['l_name'];
            $contact = Contact::create($data);
            $admin_email = $this->site_setting->where('is_active', 1)->where('key', 'admin_email')->value('value');
            // dd($admin_email);
            dispatch(new ContactMailJob($contact,$contact->email_address,$admin_email));

            // if ($contact && $contact->email_address) {
            //     Mail::to($contact->email_address)
            //         ->send(new MailForwarded($contact));
            // }
            // if (!empty($admin_email)) {
            //     Mail::to($admin_email)
            //         ->send(new MailReceived($contact));
            // }
            DB::commit();
            return redirect()->back()->with('flash_success', 'Your message has been submitted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withInput();
        };
    }

    public function jyotiCare()
    {
        $content = JyotiCare::where('is_active', 1)->first();
        return view('jyotiCare.index')
            ->withContent($content);
    }

    public function jyotiCarePost(JyotiCareRequest $request)
    {
        $data = $request->except(['captcha']);
        if ($request->hasFile('citizenship_file')) {
            $filelocation = MediaHelper::upload($request->file('citizenship_file'), 'jyoti-care');
            $data['citizenship_file'] = $filelocation['storage'];
        }
        $jyotiCare = JyotiCare::create($data);
        $admin_email = $this->site_setting->where('is_active', 1)->where('key', 'admin_email')->value('value');
        if ($jyotiCare) {
            try {
                Mail::to($jyotiCare->email_address)
                    ->send(new MailForwarded($jyotiCare));
                if (!empty($admin_email)) {
                    Mail::to($admin_email)
                        ->send(new MailReceived($jyotiCare));
                }
            } catch (Exception $e) {
            };

            return redirect()->back()->with('flash_success', 'Your message has been submitted successfully.');
        }
        return redirect()->back()->withInput();
    }
}
