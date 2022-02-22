<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\PageContent;
use App\User;
use App\Http\Traits\funcsTrait;
use App\Settings;
use App\library\CheckValueType;


class LatestExternalNews extends AbstractWidget
{
    use funcsTrait;
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'items_per_block'         => 4,
        'order_by_field_name'     => 'created_at',
        'ref_items_per_pagination'=> 10,
        'order_by_field_ordering' => 'desc',
        'show_no_items_label'     => false,
        'filter_published'        => null,
        'filter_is_featured'      => null,
        'filter_is_homepage'      => null,
    ];

    public function __construct(array $config = [])
    {
        $this->config['items_per_block']= Settings::getValue('latest_news_on_homepage', CheckValueType::cvtInteger, 8);
        $this->config['items_per_block']= isset( $config['items_per_block'] ) ? $config['items_per_block'] : $this->config['items_per_block'];

        $this->config['ref_items_per_pagination']=  isset( $config['ref_items_per_pagination'] ) ? $config['ref_items_per_pagination'] : $this->config['ref_items_per_pagination'];
        $this->config['filter_published']= isset( $config['filter_published'] ) ? $config['filter_published'] : $this->config['filter_published'];
        $this->config['filter_is_featured']= isset( $config['filter_is_featured'] ) ? $config['filter_is_featured'] : $this->config['filter_is_featured'];
        $this->config['filter_is_homepage']= isset( $config['filter_is_homepage'] ) ? $config['filter_is_homepage'] : $this->config['filter_is_homepage'];
        parent::__construct($config);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $users_tb= with(new User)->getTable();
        $page_contents_tb= with(new PageContent)->getTable();
        $latestExternalNewsData = PageContent
            ::select( $page_contents_tb.'.*',$users_tb.'.username')
            ->getByPageType( 'E' )
            ->getByPublished( $this->config['filter_published'] )
            ->getByIsFeatured( $this->config['filter_is_featured'] )
            ->getByIsHomepage( $this->config['filter_is_homepage'] )
            ->orderBy($this->config['order_by_field_name'], $this->config['order_by_field_ordering'])
            ->join($users_tb, $users_tb.'.id', '=', $page_contents_tb.'.creator_id')
            ->limit($this->config['items_per_block'])
            ->get();

        $all_external_news_count = PageContent
            ::getByPageType( 'E' )
            ->getByPublished( $this->config['filter_published'] )
            ->getByIsFeatured( $this->config['filter_is_featured'] )
            ->getByIsHomepage( $this->config['filter_is_homepage'] )
            ->join($users_tb, $users_tb.'.id', '=', $page_contents_tb.'.creator_id')
            ->count();
//        echo '<pre>$latestExternalNewsData::'.print_r($latestExternalNewsData,true).'</pre>';

//        echo '<pre>$all_external_news_count::'.print_r($all_external_news_count,true).'</pre>';
//        die("-1 XXZ");
        return view('widgets.latest_external_news', [
            'config' => $this->config,
            'latestExternalNewsData' => $latestExternalNewsData,
            'all_external_news_count' => $all_external_news_count,
        ]);

    }
}
