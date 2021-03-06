<?php

namespace DevDojo\Chatter\Controllers;

use Illuminate\Support\Facades\Auth;
use DevDojo\Chatter\Helpers\ChatterHelper as Helper;
use DevDojo\Chatter\Models\Models;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Str;

class ChatterController extends Controller
{
    public function index($slug = '')
    {
        $pagination_results = config('chatter.paginate.num_of_results');
        $order             = request()->has('sortBy') ? request()->input('sortBy') : config('chatter.order_by.discussions.by');

        if($order === 'asc' || $order === 'desc'){
            $sortBy = 'title';
        }else{
            $sortBy = config('chatter.order_by.discussions.order');
            $order = $order === 'newest' ? 'desc': 'asc';
        }

        if ($term = request()->input('q')) {
            $discussions = Models::discussion()->where('title', 'LIKE', '%' . $term . '%')
                                 ->with('user')
                                 ->with('post')
                                 ->with('postsCount')
                                 ->with('category')
                                 ->orderBy($sortBy, $order);
//                                 ->orderBy(config('chatter.order_by.discussions.order'), config('chatter.order_by.discussions.by'));
        } else {
            $discussions = Models::discussion()
                                 ->with('user')
                                 ->with('post')
                                 ->with('postsCount')
                                 ->with('category')
                                 ->orderBy($sortBy, $order);
        }

        if (isset($slug)) {
            $categoryQuery = Models::category()->query();

            if (Str::contains($slug, '/')) {
                $categoryQuery->where('slug', '=', substr($slug, strrpos($slug, '/') + 1));
            } else {
                $categoryQuery->where('slug', '=', $slug);
            }

            $category = $categoryQuery->first();

            if (isset($category->id)) {
                $current_category_id = $category->id;
                $discussions         = $discussions->where('chatter_category_id', '=', $category->id);
            } else {
                $current_category_id = null;
            }
        }

        $discussions = $discussions->paginate($pagination_results);

        $categories = Models::category()->filterCategories(null)->get();

        $chatter_editor = config('chatter.editor');

        if ($chatter_editor == 'simplemde') {
            // Dynamically register markdown service provider
            \App::register('GrahamCampbell\Markdown\MarkdownServiceProvider');
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([$discussions, $categories, $chatter_editor, $current_category_id], 200);
        }

        return view('chatter::home', compact('discussions', 'categories', 'chatter_editor', 'current_category_id'));
    }

    public function login()
    {
        if (!Auth::check()) {
            return \Redirect::to('/' . config('chatter.routes.login') . '?redirect=' . config('chatter.routes.home'))->with('flash_message', 'Please create an account before posting.');
        }
    }

    public function register()
    {
        if (!Auth::check()) {
            return \Redirect::to('/' . config('chatter.routes.register') . '?redirect=' . config('chatter.routes.home'))->with('flash_message', 'Please register for an account.');
        }
    }
}
