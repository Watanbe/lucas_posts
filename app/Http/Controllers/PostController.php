<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        // Adicionar a URL completa da imagem para cada post
        foreach ($posts as $post) {
            if ($post->image) {
                $post->image_url = asset('storage/' . $post->image);
            } else {
                $post->image_url = null;
            }
        }

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string', // base64 encoded image
        ]);

        if ($request->image) {
            $path = $this->saveBase64Image($request->image);
        } else {
            $path = null;
        }

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $path,
        ]);

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([], 404);
        }

        // Adicionar a URL completa da imagem
        if ($post->image) {
            $post->image_url = asset('storage/' . $post->image);
        } else {
            $post->image_url = null;
        }

        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string', // base64 encoded image
        ]);

        $post = Post::findOrFail($id);

        if ($request->image) {
            // Delete the old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $this->saveBase64Image($request->image);
        } else {
            $path = $post->image;
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $path,
        ]);

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Delete the image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return response()->json(null, 204);
    }

    private function saveBase64Image($base64Image)
    {
        // Extract the image extension from the base64 string
        preg_match("/^data:image\/(.*);base64/i", $base64Image, $match);
        $extension = $match[1]; // Get the extension from the base64 data

        // Remove the base64 prefix to get the actual image data
        $image = base64_decode(preg_replace('/^data:image\/(.*);base64,/', '', $base64Image));

        // Generate a unique filename
        $imageName = Str::random(10) . '.' . $extension;

        // Define the storage path
        $path = 'images/' . $imageName;

        // Store the image using the determined extension
        Storage::disk('public')->put($path, $image);

        return $path;
    }
}


