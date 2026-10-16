<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WishController extends Controller
{
    public function index()
    {
        return view('wishes.index', [
            'wishes' => Wish::with('category')->where('user_id', Auth::id())->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'url' => ['nullable', 'url', 'max:2048'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', Rule::in(['planned', 'purchased'])],
        ]);
    }

    public function store(Request $request)
    {
        Wish::create(array_merge($this->validated($request), ['user_id' => Auth::id()]));
        return redirect()->route('wishes.index')->with('success', 'Vēlme pievienota!');
    }

    public function update(Request $request, Wish $wish)
    {
        abort_unless($wish->user_id === Auth::id(), 403);
        $wish->update($this->validated($request));
        return redirect()->route('wishes.index')->with('success', 'Izmaiņas saglabātas!');
    }

    public function destroy(Wish $wish)
    {
        abort_unless($wish->user_id === Auth::id(), 403);
        $wish->delete();
        return redirect()->route('wishes.index')->with('success', 'Vēlme izdzēsta!');
    }

    public function search(Request $request)
    {
        $query = mb_substr((string) $request->query('q', ''), 0, 100);
        return response()->json(
            Wish::where('user_id', Auth::id())->where('title', 'like', '%' . $query . '%')->pluck('id')
        );
    }
}
