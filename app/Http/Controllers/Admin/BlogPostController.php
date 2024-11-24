<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $posts = BlogPost::orderBy('created_at', 'desc')->paginate(6); 
        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_name' => 'required|string|max:255',
            'blog_date' => 'required|date',
            'blog_author' => 'required|string|max:255',
            'blog_content' => 'required|string',
            'blog_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('blog_image')) {
            $timestamp = now()->timestamp;
            $filename = $timestamp . '_' . $request->file('blog_image')->getClientOriginalName();
            $imagePath = $request->file('blog_image')->storeAs('blog', $filename, 'public');
        }


         try {
                $blog = new BlogPost();
                $blog->name = $request->blog_name;
                $blog->date = $request->blog_date;
                $blog->author = $request->blog_author;
                $blog->content = $request->blog_content; 
                $blog->image = $imagePath; 
                $blog->save();

                return response()->json([
                    'message' => 'Blog created successfully!',
                    'blog' => $blog
                ]);
                
            } catch (\Exception $e) {
                    return response()->json([
                        'error' => $e->getMessage()
                    ], 500);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.blog.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'blog_name' => 'required|string|max:255',
            'blog_date' => 'required|date',
            'blog_author' => 'required|string|max:255',
            'blog_content' => 'required|string',
            'blog_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        try {

            $blog = BlogPost::findOrFail($id);
            $imagePath = $blog->image;

            if ($request->hasFile('blog_image')) {

                if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                    Storage::disk('public')->delete($blog->image);
                }
    
                $timestamp = now()->timestamp;
                $filename = $timestamp . '_' . $request->file('blog_image')->getClientOriginalName();
                $imagePath = $request->file('blog_image')->storeAs('blog', $filename, 'public');
            }
    
            $blog->name = $request->blog_name;
            $blog->date = $request->blog_date;
            $blog->author = $request->blog_author;
            $blog->content = $request->blog_content;
            $blog->image = $imagePath;

            $blog->save();
            return response()->json([
                'message' => 'Blog updated successfully!',
                'blog' => $blog,
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update blog: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            
            $blog = BlogPost::findOrFail($id);
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image); 
            }
    
            $blog->delete();

            return response()->json([
                'message' => 'Blog deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete the blog: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $posts = BlogPost::where('name', 'like', '%' . $query . '%')->get();
        return view('admin.blog._blog_list', compact('posts'));
    }

    public function getBlogs(Request $request)
    {

        try {

            $posts = BlogPost::select('name', 'date', 'author', 'content', 'image')->get();
            if ($posts->isEmpty()) {
                return response()->json(['success' => 'No blog posts found.', 'data' => []], 404);
            }
    
            $posts->transform(function ($post) {
                $post->image_url = asset('storage/' . $post->image); 
                return $post;
            });
    
            return response()->json([
                'success' => 'Blog posts fetched successfully.',
                'data' => $posts
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving blog posts: ' . $e->getMessage()
            ], 500);
        }
    }
}
