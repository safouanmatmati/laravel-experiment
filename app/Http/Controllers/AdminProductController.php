<?php

/*
 * This file is part of the Laravel Shop package.
 *
 * (c) Safouan MATMATI <safouan.matmati@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Storage;

use App\Http\Requests;
use App\Models\User;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Tag;
use App\Models\TagProduct;

/**
 * Product admin controller
 */
class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');

        // Shares variable to defined whenever we are in the backoffice
        view()->share('is_backoffice', true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        return view('admin/product/home', ['products' => Product::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        // Format datas to be used easly from template
        $brands = [];
        foreach (Brand::all() as $brand) {
            $brands[$brand->id] = $brand->name;
        }

        $tags = [];
        foreach (Tag::all() as $tag) {
            $tags[$tag->id] = $tag->name;
        }

        return view(
            'admin/product/create',
            [
                'product' => new Product(),
                'brands'  => $brands,
                'tags'    => $tags
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        // Validate
        $validator = $this->productValidator($request);
        if (true == $validator->fails()) {
            return redirect(route('admin.product.create'))
                ->withErrors($validator)->withInput();
        } else {
            $data = $request->all();

            // Store
            $product              = new Product();
            $product->id          = str_slug($data['name']);
            $product->name        = $data['name'];
            $product->description = $data['description'];
            $product->price       = $data['price'];
            $product->brand_id    = $data['brand'];

            $product->save();

            // Create tags relation
            $this->addTags($product, $data['tags']);

            // Add previews
            $this->addPreviews($product, $request->user(), $data['previews']);

            // Redirect
            $request->session()->flash('success', 'Successfully created product!');
            return redirect(route('admin.product'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $product)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        $product = Product::with(['tags', 'brand'])->findOrFail($product);

        // Format datas to be used easly from template
        $tags = [];
        foreach ($product->tags as $tag) {
            $tags[] = $tag->id;
        }

        $brands = [];
        foreach (Brand::all() as $brand) {
            $brands[$brand->id] = $brand->name;
        }

        $all_tags = [];
        foreach (Tag::all() as $tag) {
            $all_tags[$tag->id] = $tag->name;
        }

        $previews = [];
        foreach (Storage::disk('uploads')->files($this->getPreviewDirPath($product->id)) as $path) {
            $parts          = explode('/', $path);
            $previews[$path] = $parts[count($parts)-1];
        }

        return view(
            'admin/product/edit', [
            'product'  => $product,
            'brands'   => $brands,
            'all_tags' => $all_tags,
            'tags'     => $tags,
            'previews' => $previews
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        // Validate
        $validator = $this->productValidator($request);
        if (true == $validator->fails()) {
            return redirect(route('admin.product.edit', ['product' => $product]))
                ->withErrors($validator)->withInput();
        } else {
            $data = $request->all();

            // Store
            $product              = Product::with(['tags'])->findOrFail($product);
            $product->name        = $data['name'];
            $product->description = $data['description'];
            $product->price       = $data['price'];
            $product->brand_id    = $data['brand'];

            $product->save();

            // Remove old tags
            $tags_to_remove = [];
            $data['tags']   = array_combine($data['tags'], $data['tags']);

            foreach ($product->tags as $tag) {
                if (false == in_array($tag->id, $data['tags'])) {
                    $tags_to_remove[] = $tag->id;
                } else {
                    unset($data['tags'][$tag->id]);
                }
            }

            if (false == empty($tags_to_remove)) {
                TagProduct::where('tag_id', $tags_to_remove)->delete();
            }

            // Create tags relation
            $this->addTags($product, $data['tags']);

            // Add previews
            $this->addPreviews($product, $request->user(), $data['previews']);

            // Redirect
            $request->session()->flash('success', 'Successfully updated product!');
            return redirect(route('admin.product'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $product)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        // Delete product
        Product::findOrFail($product)->delete();

        // Delete previews
        $directory = $this->getPreviewDirPath($product);
        $storage   = Storage::disk('uploads');
        $storage->delete($storage->files($directory));
        $storage->deleteDirectory($directory);

        // Redirect
        $request->session()->flash('success', 'Successfully deleted product!');
        return redirect(route('admin.product'));
    }

    /**
     * Delete a product preview.
     *
     * @param  Request $request
     * @param  string  $product
     * @param  string  $preview
     * @return \Illuminate\Http\Response
     */
    public function previewDestroy(Request $request, $product, $preview)
    {
        // Redirect if not allowed
        $this->isAllowed($request);

        $storage = Storage::disk('uploads');

        foreach ($storage->files($this->getPreviewDirPath($product)) as $file) {
            if ($this->getPreviewDirPath($product).'/'.$preview == $file) {
                $storage->delete($file);
                // Redirect
                $request->session()->flash('success', 'Successfully deleted product preview!');
                return redirect(route('admin.product.edit', ['product' => $product]));
            }
        }

        // Redirect
        $request->session()->flash('danger', 'Failed to delete product preview!');
        return redirect(route('admin.product.edit', ['product' => $product]));
    }

    /**
     * Return product validator.
     *
     * @param  Request $request
     * @return Validator
     */
    private function productValidator(Request $request)
    {
        // Rules
        $rules = [
            'name'        => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'brand'       => 'required|string',
            'tags.*'      => 'required|string',
            'previews.*'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];

        return Validator::make($request->all(), $rules);
    }

    /**
     * Add tag relation.
     *
     * @param Product  $product
     * @param string[] $tags
     */
    private function addTags(Product $product, $tags)
    {
        // Create new tags relations
        foreach ($tags as $tag) {
            $tag_product             = new TagProduct();
            $tag_product->product_id = $product->id;
            $tag_product->tag_id     = $tag;

            $tag_product->save();
        }
    }

    /**
     * Add product preview file.
     *
     * @param Product  $product
     * @param User  $user
     * @param string[] $previews
     */
    private function addPreviews(Product $product, User $user, $previews)
    {
        foreach ($previews as $index => $file) {
            if (true == is_null($file)) {
                continue;
            }

            // Store image inside upload directory
            Storage::disk('uploads')->put(
                sprintf(
                    '%s/%s/%s_%s_%s.%s',
                    trim(config('PREVIEW_DIR', 'products/previews/')),
                    $product->id,
                    date('YmdHis'),
                    $user->id,
                    $index,
                    $file->guessExtension()
                ),
                file_get_contents($file->getRealPath())
            );
        }
    }

    /**
     * Return preview directory path depending on product.
     *
     * @param  string $product_id
     * @return string
     */
    private function getPreviewDirPath($product_id)
    {
        return sprintf(
            '%s/%s',
            trim(config('PREVIEW_DIR', 'products/previews/'), '/'),
            $product_id
        );
    }

    /**
     * Check if user is an admin.
     *
     * @param  Request $request
     * @return boolean
     */
    private function isAllowed(Request $request)
    {
        if (true == is_null($request->user())) {
            return redirect()->guest('login');
        }

        if (true != $request->user()->is_admin) {
            abort(403);
        }
    }
}
