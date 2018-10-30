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

use Storage;
use ProductHelper;
use Cache;
use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Tag;
use App\Models\Target;

/**
 * Shop controller
 */
class ShopController extends Controller
{
    const CACHE_MINUTES = 1;

    /**
     * Instantiate a new ShopController instance.
     * Add it to 'web' middleware.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(
            'shop/home',
            [
                'targets' => $this->getTargets(),
                'bread_crumb' => []
            ]
        );
    }

    /**
     * Show "target" page.
     *
     * @param  string $target target identifier
     * @return \Illuminate\Http\Response
     */
    public function target($target)
    {
        // Retrieve all targets
        $targets = $this->getTargets();

        // Rerieve curren one from previous targets (avoid extra query)
        foreach ($targets as $data) {
            if ($data->id == $target) {
                $target = $data;
                $found = true;
                break;
            }
        }

        // Redirect if not found
        if (true == empty($found)) {
            abort(404);
        }

        return view(
            'shop/target',
            [
                'targets'     => $this->getTargets(),
                'target'      => $target,
                'bread_crumb' => [$target]
            ]
        );
    }

    /**
     * Show "tag" page.
     *
     * @param  string $target target identifier
     * @param  string $tag    tag identifier
     * @return \Illuminate\Http\Response
     */
    public function tag($target, $tag)
    {
        // Retrieve tag
        $tag = $this->getFromCache(
            sprintf('tag:%s:%s', $target, $tag),
            function () use ($tag, $target) {
                return Tag::with(
                    [
                        'targets' => function ($query) use ($target) {
                            $query->where('id', $target);
                        },
                        'products'
                    ]
                )
                ->where('id', $tag)
                ->first();
            }
        );

        // Redirect if not found
        if (true == is_null($tag)) {
            abort(404);
        }

        // Define bread crumb "step"
        $bread_crumb = [
            $tag->targets->first(),
            $tag
        ];

        return view(
            'shop/tag',
            [
                'targets'     => $this->getTargets(),
                'bread_crumb' => $bread_crumb,
                'target'      => $tag->targets->first(),
                'tag'         => $tag
            ]
        );
    }

    /**
     * Show "tag" page.
     *
     * @param  string $target  target identifier
     * @param  string $tag     tag identifier
     * @param  string $product product identifier
     * @return \Illuminate\Http\Response
     */
    public function product($target, $tag, $product)
    {
        // Retrieve tag
        $tag = $this->getFromCache(
            sprintf('product:%s:%s:%s', $target, $tag, $product),
            function () use ($target, $tag, $product) {
                return Tag::with(
                    [
                        'targets' => function ($query) use ($target) {
                            $query->where('id', $target);
                        },
                        'products' => function ($query) use ($product) {
                            $query->where('id', $product);
                        }
                    ]
                )->where('id', $tag)
                ->first();
            }
        );

        // Redirect if not found
        if (true == is_null($tag) || 0 == count($tag->products)) {
            abort(404);
        }

        $product = $tag->products->first();

        // Define bread crumb "step"
        $bread_crumb = [
            $tag->targets->first(),
            $tag,
            $product
        ];

        return view(
            'shop/product',
            [
                'targets'     => $this->getTargets(),
                'bread_crumb' => $bread_crumb,
                'target'      => $tag->targets->first(),
                'product'     => $product,
                'previews'    => Storage::disk('uploads')->files(
                    ProductHelper::get_preview_dir_path($product->id)
                )
            ]
        );
    }

    /**
     * Return all targets.
     *
     * @return Target[]
     */
    private function getTargets()
    {
        return $this->getFromCache(
            'targets',
            function () {
                return Target::with('tags')->get();
            }
        );
    }

    /**
     * Retrive data from cache.
     * Define life time.
     *
     * @param  string $key  key of cached data
     * @param  mixed  $data data to cache
     * @return mixed       cached data
     */
    private function getFromCache($key, $data)
    {
        // If user is an admin, return data
        if (false== is_null(Auth::user()) && true == Auth::user()->is_admin) {
            if (true == is_callable($data)) {
                return call_user_func($data);
            }

            return $data;
        }

        // Otherwise get it from cache
        return Cache::store('redis')->remember($key, self::CACHE_MINUTES, $data);
    }
}
