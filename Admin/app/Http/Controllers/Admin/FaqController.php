<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function addnewfaq()
    {
        return view('faq.addfaq');
    }

    public function savefaq(Request $request)
    {

        if ($request->question != "" && $request->answer != "") {
            $Contact = new Faq;
            $Contact->question = $request->question;
            $Contact->answer = $request->answer;
            $Contact->save();

            if ($Contact) {
                return redirect('admin/faq')->with('success', 'FAQ added successfully.');
            } else {
                return redirect('admin/faq')->with('error', 'FAQ could not be added Successfully.');
            }
        } else {
            return back()->with('error', 'Field required!');
        }

    }

    public function faq()
    {
        $data['contents'] = Faq::paginate(10);
        return view('faq.faqlist', $data);
    }

    public function editfaq(Request $request)
    {
        $id = $request->id;
        $onenews = Faq::where('_id', $id)->first();
        // dd($onenews);
        return view('faq.editfaq', [
            'id' => $id,
            'content' => $onenews,
        ]);
    }

    public function updatefaq(Request $request)
    {
        // dd(\Session::all());

        if ($request->id != "" && $request->question != "" && $request->answer != "") {
            $update = Faq::where(['_id' => $request->id])->update(['question' => $request->question, 'answer' => $request->answer]);
            return redirect('admin/faq')->with('success', 'Faq updated uuccessfully.');
        } else {
            return redirect('admin/faq')->with('warning', 'Field require or try again.');
        }

    }
}