<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function homepage()
    {
        $categories = Category::isShow()->get();
        $popularPosts = Post::with('images', 'category')->isApproved()->isPopular()->get();
        $recentPosts = Post::with('images', 'category')->isApproved()->orderBy('created_at', 'desc')
            ->limit(config('custom.recent_post_num'))->get();
        $recentPostofTravle = Post::with('images', 'category')
            ->where('category_id', config('custom.category_text.travel'))
            ->isApproved()->orderBy('created_at', 'desc')
            ->limit(config('custom.recent_post_by_category_num'))->get();

        $recentPostofFood = Post::with('images', 'category')
            ->where('category_id', config('custom.category_text.food'))
            ->isApproved()->orderBy('created_at', 'desc')
            ->limit(config('custom.recent_post_by_category_num'))->get();

        $recentPostofFashion = Post::with('images', 'category')
            ->where('category_id', config('custom.category_text.fashion'))
            ->isApproved()->orderBy('created_at', 'desc')
            ->limit(config('custom.recent_post_by_category_num'))->get();

        $postHotinSidebar = $this->listPostHotinSidebar();

        return view('client.home.index', compact(
            'categories',
            'popularPosts',
            'recentPosts',
            'recentPostofTravle',
            'recentPostofFood',
            'recentPostofFashion',
            'postHotinSidebar',
        ));
    }

    public function postDetails($categorySlug, $postSlug)
    {
        $categories = Category::isShow()->get();
        $post = Post::isApproved()->where('slug', $postSlug)->first();
        $postHotinSidebar = $this->listPostHotinSidebar();

        return view('client.post.post-details', compact('categories', 'post', 'postHotinSidebar'));
    }

    public function getPostbyCategory($categorySlug)
    {
        $categories = Category::isShow()->get();
        $category = Category::where('slug', $categorySlug)->first();
        $posts = Post::where('category_id', $category->id)->isApproved()
            ->orderBy('created_at', 'desc')->paginate(config('custom.per_page'));
        $postHotinSidebar = $this->listPostHotinSidebar();

        return view('client.post.post-by-category', compact(
            'categories',
            'category',
            'posts',
            'postHotinSidebar',
        ));
    }

    public function listPostHotinSidebar()
    {
        $postHotinSidebar = Post::with('images', 'category')->isApproved()
            ->orderBy('created_at', 'desc')
            ->limit(config('custom.post_hot_in_sidebar_num'))->get();

        return $postHotinSidebar;
    }

    public function searchPost(Request $request)
    {
        $keyword = $request->q;
        $listofSearchPost = Post::with('images', 'category')->where('name', 'like', "%$keyword%")
            ->isApproved()->orderBy('created_at', 'desc')
            ->paginate(config('custom.per_page'));
        $categories = Category::isShow()->get();
        $postHotinSidebar = $this->listPostHotinSidebar();

        return view('client.post.search', compact('categories', 'listofSearchPost', 'keyword', 'postHotinSidebar'));
    }
}
