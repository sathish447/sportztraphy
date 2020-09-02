<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\FaqRequest;
use App\Models\Admin;
use App\Models\CMS;
use App\Models\ErcTokens;
use App\Models\Faq;
use App\Models\Features;
use App\Models\SocialMedia;
use App\Models\TwaOption;
use App\Models\UserpanelSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function logo()
    {
        $terms = CMS::index();

        return view('settings.logo', ['logo' => $terms]);
    }

    public function tc()
    {
        $terms = CMS::index();

        return view('settings.tc', ['terms' => $terms]);
    }

    public function update_terms(Request $request)
    {
        $update = CMS::updateTerms($request);
        return back()->with('status', $update);
    }

    public function privacy()
    {
        $terms = CMS::index();

        return view('settings.privacy', ['privacy' => $terms]);
    }

    public function updatePrivacy(Request $request)
    {

        $update = CMS::updatePrivacy($request);

        return back()->with('status', $update);
    }

    public function aboutus()
    {
        $aboutus = CMS::index();

        return view('settings.aboutus', ['aboutus' => $aboutus]);
    }

    public function updateAbout(Request $request)
    {

        $update = CMS::updateAbout($request);

        return back()->with('status', $update);

    }

    public function features()
    {
        $features = Features::get();

        return view('settings.features')->with('features', $features);
    }

    public function features_settings(Request $request)
    {

        $features = Features::updateFeatures($request);

        return back()->with('status', $features);
    }

    public function faq()
    {
        $faq = Faq::get();

        return view('settings.faq')->with('faq', $faq);
    }

    public function faq_add()
    {
        return view('settings.faq_add');
    }
    public function faq_save(FaqRequest $request)
    {
        $faq = Faq::saveFaq($request);

        return redirect('admin/faq')->with('success', 'Added Successfully');
    }

    public function faq_edit($id)
    {
        $faq = Faq::edit($id);

        return view('settings.faq_edit')->with('faq', $faq);
    }

    public function faq_update(Request $request)
    {
        $faq = Faq::faqUpdate($request);

        return redirect('admin/faq')->with('success', $faq);
    }

    public function socialMedia()
    {
        $socialMedia = SocialMedia::index();
        return view('settings.social_media')->with('link', $socialMedia);
    }

    public function saveSocialMedia(Request $request)
    {
        $socialMedia = SocialMedia::saveSocialMedia($request);
        return back()->with('success', 'Social Media Setting Updated Successfully!');
    }

    public function adminChangePassword(ChangePasswordRequest $request)
    {

        $admin = Admin::find(\Session::get('adminId'));
        $hashedPassword = $admin->password;

        if (\Hash::check($request->oldpassword, $hashedPassword)) {
            $admin->password = \Hash::make($request->newpassword);
            $admin->save();

            return back()->with('success', 'Password changed successfully');
        } else {
            return back()->with('error', 'Given Old Password was wrong!!!');
        }
        return back();
    }

    public function adminChangeEmail(Request $request)
    {
        $rules = [
            'email' => 'required',
        ];

        $messages = [
            'email.required' => 'New User Name is required.',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator);
        }

        $adminDetail = Admin::where('_id', \Session::get('adminId'))->first();
        $adminDetail->email = $request->email;
        if ($adminDetail->save()) {
            return back()->with('status', 'Email updated successfully.');
        } else {
            return back()->with('success', 'Email updated failed!!!.');
        }

        return back();

    }

    public function userpanelSettings()
    {
        $settings = UserpanelSettings::index();

        $fwofa_option = TwaOption::list();

        return view('settings.userpanel_settings')->with('settings', $settings)->with('two_options', $fwofa_option);
    }

    public function saveUserpanelSettings(Request $request)
    {
        $where = array();
        $twofa_lis = TwaOption::list();
        foreach ($twofa_lis as $key => $value) {

            $concat = 'option_' . $value->name;
            $where = array('id' => $value->id);
            $change = array('enable_status' => $request->$concat);

            $update = TwaOption::updatee($where, $change);
        }

        $socialMedia = UserpanelSettings::saveUserpanel($request);

        return back()->with('success', 'Updated Successfully');
    }

    public function token()
    {
        $token = ErcTokens::list();

        return view('settings.token')->with('token', $token);
    }

    public function addToken()
    {
        return view('settings.add_token');
    }

    public function saveToken(Request $request)
    {
        $save = ErcTokens::add($request);

        return back()->with('success', 'Added Successfully');
    }

    public function editToken($id)
    {
        $view = ErcTokens::view($id);

        return view('settings.view_token')->with('details', $view);
    }

    public function updateToken(Request $request)
    {
        $update = ErcTokens::updated($request);

        return back()->with('success', 'Updated Successfully');
    }

    public function twoFA()
    {
        $token = TwaOption::list();

        return view('settings.2fa_option.twofa')->with('token', $token);
    }

    public function addtwoFA()
    {
        return view('settings.2fa_option.add_twofa');
    }

    public function savetwofa(Request $request)
    {
        $save = TwaOption::add($request);

        return back()->with('success', 'Added Successfully');
    }

    public function edittwofa($id)
    {
        $view = TwaOption::view($id);

        return view('settings.2fa_option.edit_twofa')->with('details', $view);
    }

    public function updateTwofa(Request $request)
    {

        $where = array('id' => $request->id);
        $change = array('enable_status' => $request->two_option);

        $update = TwaOption::updatee($where, $change);

        return back()->with('success', 'Updated Successfully');
    }

}