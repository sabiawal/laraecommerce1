<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Auth\Events\Validated;
use App\Http\Requests\CategoryFormRequest;


class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(CategoryFormRequest $request)
    {
        $ValidatedData = $request->validated();

        $category = new Category;
        $category->name = $ValidatedData['name'];
        $category->slug = Str::slug($ValidatedData['slug']);
        $category->description = $ValidatedData['description'];

        if($request->hasFile('image')){
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/category/',$filename);

            $category->image = $filename;
        }


        $category->meta_title = $ValidatedData['meta_title'];
        $category->meta_keyword = $ValidatedData['meta_keyword'];
        $category->meta_description = $ValidatedData['meta_description'];

        $category->status = $request->status == true ? '1':'0';

        $category->save();
        return redirect('admin/category')->with('message','Category Added Successfully');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryFormRequest $request, $category)
    {
        $ValidatedData = $request->validated();


        $category = Category::findOrFail($category);

        $category->name = $ValidatedData['name'];
        $category->slug = Str::slug($ValidatedData['slug']);
        $category->description = $ValidatedData['description'];

        if($request->hasFile('image')){

            $path = 'uploads/category/'.$category->image;

            if(File::exists($path)){
                File::delete($path);
            }

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time().'.'.$ext;

            $file->move('uploads/category/',$filename);

            $category->image = $filename;
        }


        $category->meta_title = $ValidatedData['meta_title'];
        $category->meta_keyword = $ValidatedData['meta_keyword'];
        $category->meta_description = $ValidatedData['meta_description'];

        $category->status = $request->status == true ? '1':'0';

        $category->update();
        return redirect('admin/category')->with('message','Category Updated Successfully');
    }
}
