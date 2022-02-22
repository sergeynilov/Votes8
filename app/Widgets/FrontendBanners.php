<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Banner;
use App\Http\Traits\funcsTrait;


class FrontendBanners extends AbstractWidget
{
    use funcsTrait;
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'order_by_field_name'     => 'created_at',
        'order_by_field_ordering' => 'desc',
    ];

    public function run()
    {

        $tempBanners = Banner
            ::getByActive(true)
            ->orderBy('ordering', 'desc')
            ->get()
            ->map(function ($banner) {
                    return [
                        'id'           => $banner->id,
                        'text'         => $banner->text,
                        'logo'         => $banner->logo,
                        'short_descr'  => $banner->short_descr,
                        'url'          => $banner->url,
                        'view_type'    => $banner->view_type,
                        'created_at'   => $banner->created_at
                    ];
            })
            ->all();
        
        return view('widgets.frontend_banners', [
            'config'        => $this->config,
            'activeBanners'   => $tempBanners
        ]);
    }
}
