<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function admin_dashboard()
    {
        return view ('admin.index');
    }


    public function category () 
    {
        //to get every category added inside the category view.. 
        $categories = Category::orderBy('created_at', 'DESC')->paginate(2);
        return view ('admin.category', compact ('categories')) ;
    }


    //to add a category
    public function add_category(Request $request)
    {
         //validation of input, This method if not flexible because it doesn't allow us edit anything. 
         $validator = $request->validate([
            'category' => 'required|unique:categories,category',
        ],[ //making error messages
            'category.unique' => 'Category name  already exist',
        ]);



        //to save the inputs.. 

        Category::create($validator); 


        return redirect()-> back()->with('success', 'Category added successfully');

       
    }

    //to delete a category

    public function deleteCategory($id)
    {
         $data = Category::find($id);
         $data->delete();
         return redirect()->back()->with('success', 'Category deleted successfuly');
    }

    //logout admin
    public function admin_logout() {
        Auth::guard('web')-> logout();
        return redirect('/')->with('message', 'You are logged out');
    }

    public function create_product() {
        
        return view('admin.create_product');
    }

    // //to add products
    // public function addProduct(Request $request)
    // {
        
        

    //       $product->save(); 
    //       return redirect('admin/dashboard')-> back()->with('message', 'Product added successfully');
          


         


       
    // }


    public function addProduct(Request $request){
    try {
        // Your existing code for processing the product creation
         //validation of input
         $request->validate([
            'productName' => 'required|max:255',
            'productCategory' => 'required|max:255',
            'productImage' => ['nullable', 'file', 'max:10000'],
            'productDescription' => 'required',
            'manufacturerName' => 'required|max:255',
            'status' => 'required|max:255',
            'productPrice' => 'required',
            'discountPrice' => ['nullable'],
            'quantity' => 'nullable|max:255',
            'warranty' => 'required|max:255',

          ]);

          $product = new Product();
          $product->productName = $request->productName;
          $product->productCategory = $request->productCategory;
          $product->productDescription = $request->productDescription;
          $product->manufacturerName = $request->manufacturerName;
          $product->status = $request->status;
          $product->productPrice = $request->productPrice;
          $product->discountPrice = $request->discountPrice;
          $product->quantity = $request->quantity;
          $product->warranty = $request->warranty; 
          $product->featuredProduct = $request->featuredProduct;



          // TO ADD IMAGE 

          if($request->hasFile('productImage')){
            $image = $request->file('productImage');
            $productImage = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('productFolder'), $productImage);
            $product->productImage = $productImage;
          }
        // Save the product
        $product->save();

        return redirect()->back()->with('success', 'Product added successfully');
    } catch (\Exception $e) {
        // Log the error for debugging
       dd($e);
        return back()->withInput()->withErrors(['error' => 'An error occurred while adding the product']);
    }
}


public function products () {
    return view('admin/products');
}

 //to delete a product

 public function deleteProduct($id)
 {
      $data = Product::find($id);
      $data->delete();
      return redirect()->back()->with('success', 'Product deleted successfuly');
 }
 
}
