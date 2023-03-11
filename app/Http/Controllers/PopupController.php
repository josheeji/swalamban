<?php

namespace App\Http\Controllers;

use App\Repositories\PopupRepository;
use Illuminate\Http\Request;
use Spatie\SchemaOrg\Schema;

class PopupController extends Controller
{
    public function __construct(PopupRepository $popup)
    {
        $this->popup = $popup;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!$popup = $this->popup->where('slug', $slug)->where('is_active', 1)->first()) {
            abort(404);
        }
        $schema = Schema::article()->articleBody($popup->description)
            ->wordCount(strlen($popup->description))
            ->about($popup->title)
            ->headline($popup->title)
            ->author('Kumari Bank Limited')
            ->creator('Kumari Bank Limited')
            ->dateCreated($popup->created_at)
            ->dateModified($popup->updated_at)
            ->datePublished($popup->created_at)
            ->editor('Kumari Bank Limited')
            ->keywords($popup->title)
            ->publisher(Schema::organization()->name('Kumari Bank Limited'))
            ->text($popup->description)
            ->description($popup->description)
            ->name($popup->title)
            ->image(asset('images/logo.png'))
            ->url(url()->full());


        return view('popup.show', ['popup' => $popup, 'schema' => $schema]);
    }
}